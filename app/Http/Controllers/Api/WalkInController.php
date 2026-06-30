<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\WalkIn;
use App\Models\Queue;
use App\Models\Service;
use App\Models\Stylist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WalkInController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id',
            'stylist_id' => 'required|exists:stylists,id',
            'outlet_id' => 'required|exists:outlets,id',
        ]);

        // Find or create customer by WhatsApp
        $customer = Customer::firstOrCreate(
            ['whatsapp' => $request->whatsapp],
            [
                'full_name' => $request->customer_name,
                'phone' => $request->whatsapp,
                'membership_status' => 'regular'
            ]
        );

        $service = Service::findOrFail($request->service_id);
        $stylist = Stylist::findOrFail($request->stylist_id);

        // Create virtual booking
        $booking = Booking::create([
            'booking_code' => 'WI-' . strtoupper(Str::random(8)),
            'customer_id' => $customer->id,
            'outlet_id' => $request->outlet_id,
            'stylist_id' => $request->stylist_id,
            'booking_date' => Carbon::today()->toDateString(),
            'booking_time' => Carbon::now()->toTimeString(),
            'duration' => $service->duration,
            'total_price' => $service->price,
            'discount_amount' => 0.00,
            'final_price' => $service->price,
            'status' => 'checked_in',
            'service_ids' => [$request->service_id],
            'source' => 'kiosk',
        ]);

        // Generate Queue Number
        $queueCount = Queue::whereDate('created_at', Carbon::today())->count() + 1;
        $queueNumber = 'W-' . str_pad($queueCount, 3, '0', STR_PAD_LEFT);

        $queue = Queue::create([
            'booking_id' => $booking->id,
            'queue_number' => $queueNumber,
            'status' => 'waiting',
            'estimated_wait_time' => $service->duration,
        ]);

        // Save WalkIn Log
        WalkIn::create([
            'customer_name' => $request->customer_name,
            'whatsapp' => $request->whatsapp,
            'service_id' => $request->service_id,
            'stylist_id' => $request->stylist_id,
            'queue_number' => $queueNumber,
            'status' => 'waiting',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran walk-in berhasil!',
            'queue_number' => $queueNumber,
            'estimated_wait_time' => $queue->estimated_wait_time,
            'booking' => $booking
        ], 201);
    }
}
