<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\Queue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckInController extends Controller
{
    public function show($code)
    {
        $booking = Booking::with(['customer', 'outlet', 'stylist'])
            ->where('booking_code', $code)
            ->firstOrFail();

        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string|exists:bookings,booking_code',
        ]);

        $booking = Booking::where('booking_code', $request->booking_code)->firstOrFail();

        if ($booking->status === 'checked_in') {
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah melakukan check-in sebelumnya.'
            ], 422);
        }

        if ($booking->status !== 'booked') {
            return response()->json([
                'success' => false,
                'message' => 'Status booking tidak valid untuk check-in.'
            ], 422);
        }

        // Update booking status
        $booking->status = 'checked_in';
        $booking->save();

        // Create Check-In entry
        CheckIn::create([
            'booking_id' => $booking->id,
            'check_in_time' => now(),
            'status' => 'checked_in',
        ]);

        // Generate Queue number e.g. A-12
        $queueCount = Queue::whereDate('created_at', Carbon::today())->count() + 1;
        $queueNumber = 'Q-' . str_pad($queueCount, 3, '0', STR_PAD_LEFT);

        $queue = Queue::create([
            'booking_id' => $booking->id,
            'queue_number' => $queueNumber,
            'status' => 'waiting',
            'estimated_wait_time' => $booking->duration,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Check-in berhasil!',
            'booking' => $booking,
            'queue' => $queue
        ]);
    }

    public function manual(Request $request)
    {
        // Staff check-in endpoint
        return $this->store($request);
    }
}
