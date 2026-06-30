<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Queue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function queue(Request $request)
    {
        $today = Carbon::today()->toDateString();

        // Get all queues for today
        $queues = Queue::with(['booking.customer', 'booking.stylist', 'booking.outlet'])
            ->whereDate('created_at', $today)
            ->get();

        // Map queue status categories
        $waiting = $queues->where('status', 'waiting')->values();
        $serving = $queues->where('status', 'serving')->values();
        $completed = $queues->where('status', 'completed')->values();

        // Calculate receptionist stats
        $totalBookings = Booking::whereDate('booking_date', $today)->count();
        $checkedIn = Booking::whereDate('booking_date', $today)->where('status', 'checked_in')->count();
        $inProgress = Booking::whereDate('booking_date', $today)->where('status', 'in_progress')->count();
        $done = Booking::whereDate('booking_date', $today)->where('status', 'completed')->count();
        $revenue = Booking::whereDate('booking_date', $today)->where('status', 'completed')->sum('final_price');

        return response()->json([
            'summary' => [
                'total_bookings' => $totalBookings,
                'checked_in' => $checkedIn,
                'in_progress' => $inProgress,
                'completed' => $done,
                'revenue' => $revenue
            ],
            'queues' => [
                'waiting' => $waiting,
                'serving' => $serving,
                'completed' => $completed
            ]
        ]);
    }

    public function updateQueueStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:waiting,serving,completed,skipped'
        ]);

        $queue = Queue::findOrFail($id);
        $queue->status = $request->status;
        $queue->save();

        // Update corresponding booking status if linked
        if ($queue->booking) {
            $booking = $queue->booking;
            if ($request->status === 'serving') {
                $booking->status = 'in_progress';
            } elseif ($request->status === 'completed') {
                $booking->status = 'completed';
            } elseif ($request->status === 'skipped') {
                $booking->status = 'no_show';
            }
            $booking->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Status antrean berhasil diperbarui.',
            'queue' => $queue
        ]);
    }
}
