<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use App\Models\StylistLeaveRequest;
use App\Models\StylistSchedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StylistController extends Controller
{
    public function index(Request $request)
    {
        $query = Stylist::with('outlet')->where('is_active', true);

        if ($request->has('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }

        $stylists = $query->get();
        return response()->json($stylists);
    }

    public function availability($id, Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d'
        ]);

        $stylist = Stylist::findOrFail($id);
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        // Check if stylist is on leave
        $onLeave = StylistLeaveRequest::where('stylist_id', $id)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();

        if ($onLeave) {
            return response()->json([
                'available_slots' => [],
                'status' => 'leave',
                'message' => 'Stylist is currently on leave.'
            ]);
        }

        // Get operational hours from schedule JSON or default
        $schedule = $stylist->schedule[$dayOfWeek] ?? ['start' => '10:00', 'end' => '20:00'];
        
        // If stylist doesn't work on this day
        if (empty($schedule) || !isset($schedule['start'])) {
            return response()->json([
                'available_slots' => [],
                'status' => 'off',
                'message' => 'Stylist is off duty on this day.'
            ]);
        }

        // Find existing bookings on this date
        $bookings = Booking::where('stylist_id', $id)
            ->where('booking_date', $date->toDateString())
            ->whereIn('status', ['booked', 'checked_in', 'in_progress'])
            ->get();

        // Generate time slots (Forced to hourly intervals, 60-minute duration per customer)
        $start = Carbon::parse($schedule['start']);
        $end = Carbon::parse($schedule['end']);
        $interval = 60; 

        $slots = [];
        $allSlots = [];

        while ($start->copy()->addMinutes($interval) <= $end) {
            $slotStart = $start->format('H:i');

            // Check if slot overlaps with any active booking
            $isBooked = false;
            foreach ($bookings as $booking) {
                // If customer hasn't checked in within 15 minutes past the start time, booking is forfeited
                if ($booking->status === 'booked') {
                    $bookingDateTime = Carbon::parse($booking->booking_date . ' ' . $booking->booking_time);
                    if ($bookingDateTime->copy()->addMinutes(15)->lt(now())) {
                        $booking->status = 'canceled';
                        $booking->save();
                        continue; // Skip evaluating this canceled booking
                    }
                }

                $bStart = Carbon::parse($booking->booking_time);
                $bEnd = $bStart->copy()->addMinutes($booking->duration);
                
                $slotStartCarbon = Carbon::parse($slotStart);
                
                if ($slotStartCarbon >= $bStart && $slotStartCarbon < $bEnd) {
                    $isBooked = true;
                    break;
                }
            }

            // Do not show past timeslots if the date is today
            $isPast = false;
            if ($date->isToday()) {
                if (Carbon::parse($slotStart)->setTimezone(config('app.timezone', 'UTC'))->lt(now())) {
                    $isPast = true;
                }
            }

            $allSlots[] = [
                'time' => $slotStart,
                'is_available' => !$isBooked && !$isPast,
                'reason' => $isBooked ? 'booked' : ($isPast ? 'past' : 'available')
            ];

            if (!$isBooked && !$isPast) {
                $slots[] = $slotStart;
            }

            $start->addMinutes($interval);
        }

        return response()->json([
            'available_slots' => $slots,
            'all_slots' => $allSlots,
            'status' => 'available',
            'date' => $date->toDateString()
        ]);
    }

    public function bookedDates($id)
    {
        $dates = Booking::where('stylist_id', $id)
            ->whereIn('status', ['booked', 'checked_in', 'in_progress'])
            ->whereDate('booking_date', '>=', now()->toDateString())
            ->pluck('booking_date')
            ->unique()
            ->values();

        return response()->json($dates);
    }

    public function schedule(Request $request)
    {
        // Stylist checking their schedule
        $user = $request->user();
        $stylist = Stylist::where('user_id', $user->id)->first() ?? 
                   Stylist::where('name', 'like', '%' . $user->name . '%')->first() ?? 
                   Stylist::first();

        $query = Booking::with(['customer', 'outlet', 'details.service'])
            ->where('stylist_id', $stylist->id);

        if ($request->has('history') && $request->history == 'true') {
            $query->where(function ($q) {
                $q->whereDate('booking_date', '<', now()->toDateString())
                  ->orWhereIn('status', ['completed', 'cancelled', 'no_show']);
            });
            $query->orderBy('booking_date', 'desc')->orderBy('booking_time', 'desc');
        } else {
            // Active schedule: today or in the future that are not completed/cancelled
            $query->whereDate('booking_date', '>=', now()->toDateString())
                  ->whereNotIn('status', ['completed', 'cancelled', 'no_show']);
            $query->orderBy('booking_date', 'asc')->orderBy('booking_time', 'asc');
        }

        $bookings = $query->get();

        $stats = [
            'today_count' => Booking::where('stylist_id', $stylist->id)->whereDate('booking_date', now()->toDateString())->whereNotIn('status', ['completed', 'cancelled', 'no_show'])->count(),
            'completed_count' => Booking::where('stylist_id', $stylist->id)->where('status', 'completed')->count(),
            'rating' => (float) ($stylist->rating ?? 5.0),
        ];

        return response()->json([
            'stylist' => $stylist,
            'bookings' => $bookings,
            'stats' => $stats
        ]);
    }

    public function requestLeave(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $user = $request->user();
        $stylist = Stylist::where('user_id', $user->id)->first() ?? 
                   Stylist::where('name', 'like', '%' . $user->name . '%')->first() ?? 
                   Stylist::first();

        $leave = StylistLeaveRequest::create([
            'stylist_id' => $stylist->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'leave' => $leave
        ], 201);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'name' => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|between:0,5',
            'experience_years' => 'nullable|integer',
            'schedule' => 'nullable|array',
            'avatar_url' => 'nullable|url',
        ]);

        $stylist = Stylist::create($validated);
        return response()->json($stylist, 201);
    }

    public function update(Request $request, $id)
    {
        $stylist = Stylist::findOrFail($id);

        $validated = $request->validate([
            'outlet_id' => 'sometimes|required|exists:outlets,id',
            'name' => 'sometimes|required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'rating' => 'nullable|numeric|between:0,5',
            'experience_years' => 'nullable|integer',
            'schedule' => 'nullable|array',
            'avatar_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $stylist->update($validated);
        return response()->json($stylist);
    }

    public function destroy($id)
    {
        $stylist = Stylist::findOrFail($id);
        $stylist->delete();
        return response()->json(['message' => 'Stylist deleted successfully']);
    }
}
