<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Stylist;
use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendBookingNotification;

class BookingController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'stylist_id' => 'required|exists:stylists,id',
            'date' => 'required|date',
            'duration' => 'required|integer|min:15'
        ]);

        $stylist = Stylist::find($request->stylist_id);
        $date = Carbon::parse($request->date);

        // Get booked slots (evaluating active bookings only)
        $bookings = Booking::where('stylist_id', $request->stylist_id)
            ->where('booking_date', $date->toDateString())
            ->whereIn('status', ['booked', 'checked_in', 'in_progress'])
            ->get();

        $bookedSlots = [];
        foreach ($bookings as $booking) {
            // Apply 15-minute cancellation rule
            if ($booking->status === 'booked') {
                $bookingDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
                if ($bookingDateTime->copy()->addMinutes(15)->lt(now())) {
                    $booking->status = 'canceled';
                    $booking->save();
                    continue; // Skip
                }
            }
            $bookedSlots[] = Carbon::parse($booking->booking_time)->format('H:i');
        }

        // Generate available slots (Force to 60-minute duration)
        $availableSlots = [];
        $start = Carbon::parse($stylist->schedule[$date->dayOfWeek]['start'] ?? '09:00');
        $end = Carbon::parse($stylist->schedule[$date->dayOfWeek]['end'] ?? '21:00');
        $interval = 60; 

        while ($start < $end) {
            $slot = $start->format('H:i');
            if (!in_array($slot, $bookedSlots)) {
                $availableSlots[] = $slot;
            }
            $start->addMinutes($interval);
        }

        return response()->json([
            'available_slots' => $availableSlots,
            'date' => $date->toDateString()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'stylist_id' => 'required|exists:stylists,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|date_format:H:i',
            'customer.full_name' => 'required|string|max:255',
            'customer.phone' => 'nullable|string|max:20',
            'customer.whatsapp' => 'required|string|max:20',
            'customer.email' => 'nullable|email|max:255',
            'customer.date_of_birth' => 'nullable|date',
            'customer.gender' => 'nullable|in:male,female'
        ]);

        try {
            DB::beginTransaction();

            $customerData = $request->customer;
            if (empty($customerData['phone'])) {
                $customerData['phone'] = $customerData['whatsapp'];
            }

            // Find or create customer
            $customer = Customer::firstOrCreate(
                ['whatsapp' => $customerData['whatsapp']],
                $customerData
            );

            // Calculate total duration and price
            $services = Service::whereIn('id', $request->service_ids)->get();
            $totalDuration = $services->sum('duration');
            $totalPrice = $services->sum('price');

            // Apply promotions (including birthday)
            $discount = $this->calculateDiscount($customer, $request->booking_date, $totalPrice);

            // Create booking
            $booking = Booking::create([
                'booking_code' => 'BK-' . strtoupper(Str::random(8)),
                'customer_id' => $customer->id,
                'outlet_id' => $request->outlet_id,
                'stylist_id' => $request->stylist_id,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'duration' => $totalDuration,
                'total_price' => $totalPrice,
                'discount_amount' => $discount,
                'final_price' => $totalPrice - $discount,
                'status' => 'booked',
                'service_ids' => $request->service_ids,
                'source' => 'online'
            ]);

            // Generate QR code
            $qrCode = $this->generateQRCode($booking->booking_code);
            $booking->qr_code_url = $qrCode;
            $booking->save();

            // Update customer
            $customer->increment('total_visits');
            $customer->total_spending += $booking->final_price;
            $customer->last_visit_at = now();
            $customer->save();

            // Queue notifications safely
            try {
                SendBookingNotification::dispatch($booking, $customer);
            } catch (\Throwable $e) {
                Log::error("Failed to dispatch booking notification job: " . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'booking' => $booking,
                'qr_code' => $qrCode,
                'customer' => $customer
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateDiscount($customer, $bookingDate, $totalPrice)
    {
        $discount = 0;

        // Birthday promo
        if ($customer->date_of_birth) {
            $birthday = Carbon::parse($customer->date_of_birth);
            $bookingDate = Carbon::parse($bookingDate);

            if ($birthday->month == $bookingDate->month &&
                $birthday->day == $bookingDate->day) {
                // Apply birthday discount (e.g., 20%)
                $discount = $totalPrice * 0.2;
            }
        }

        // Apply other promotions
        // ...

        return $discount;
    }

    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'stylist', 'outlet']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        return response()->json($query->orderBy('booking_date', 'desc')->orderBy('booking_time', 'desc')->get());
    }

    public function show($id)
    {
        $booking = Booking::with(['customer', 'stylist', 'outlet'])->findOrFail($id);
        return response()->json($booking);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'sometimes|required|in:booked,checked_in,in_progress,completed,cancelled,no_show',
            'booking_date' => 'sometimes|required|date',
            'booking_time' => 'sometimes|required|date_format:H:i',
            'notes' => 'nullable|string',
            'stylist_id' => 'sometimes|required|exists:stylists,id',
        ]);
        
        $booking->update($validated);
        
        return response()->json($booking);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }

    private function generateQRCode($code)
    {
        // Implement QR code generation
        // Use SimpleSoftwareIO\QrCode or similar
        return "https://example.com/qr/" . $code;
    }
}
