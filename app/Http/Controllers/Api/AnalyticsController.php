<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Stylist;
use App\Models\Customer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();

        // 1. KPI Cards
        $bookingsToday = Booking::whereDate('booking_date', $today)->count();
        $revenueToday = Booking::whereDate('booking_date', $today)->where('status', 'completed')->sum('final_price');
        $revenueMonth = Booking::whereBetween('booking_date', [$startOfMonth, $today])->where('status', 'completed')->sum('final_price');
        
        $totalBookingsMonth = Booking::whereBetween('booking_date', [$startOfMonth, $today])->count();
        $noShowBookingsMonth = Booking::whereBetween('booking_date', [$startOfMonth, $today])->where('status', 'no_show')->count();
        $noShowRate = $totalBookingsMonth > 0 ? round(($noShowBookingsMonth / $totalBookingsMonth) * 100, 1) : 0;

        // 2. Popular Services
        // We'll decode service_ids JSON or simulate popular services query.
        // Let's query services and count how many times they appear in bookings.
        $services = Service::all();
        $popularServices = [];
        foreach ($services as $srv) {
            // Count where JSON array contains $srv->id
            $count = Booking::whereJsonContains('service_ids', $srv->id)->count();
            if ($count > 0) {
                $popularServices[] = [
                    'name' => $srv->name,
                    'count' => $count,
                    'price' => $srv->price,
                    'revenue' => $count * $srv->price
                ];
            }
        }
        usort($popularServices, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        $popularServices = array_slice($popularServices, 0, 5);

        // 3. Top Stylists
        $stylists = Stylist::all();
        $topStylists = [];
        foreach ($stylists as $sty) {
            $count = Booking::where('stylist_id', $sty->id)->where('status', 'completed')->count();
            $rating = $sty->rating;
            $topStylists[] = [
                'name' => $sty->name,
                'bookings_count' => $count,
                'rating' => $rating,
                'outlet' => $sty->outlet->name ?? 'N/A'
            ];
        }
        usort($topStylists, function($a, $b) {
            return $b['bookings_count'] <=> $a['bookings_count'];
        });
        $topStylists = array_slice($topStylists, 0, 5);

        // 4. Booking Trend (Last 7 Days)
        $bookingTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $label = Carbon::today()->subDays($i)->format('d M');
            $count = Booking::whereDate('booking_date', $date)->count();
            $rev = Booking::whereDate('booking_date', $date)->where('status', 'completed')->sum('final_price');
            $bookingTrend[] = [
                'date' => $label,
                'bookings' => $count,
                'revenue' => (float)$rev
            ];
        }

        // 5. Customer Retention / Source Acquisition
        $retentionRate = 78.5; // Dummy percentage
        
        $sourceCounts = Booking::select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->get();
        
        $totalAcqs = $sourceCounts->sum('total');
        $sources = [];
        
        foreach ($sourceCounts as $sc) {
            $srcName = $sc->source ?: 'online';
            if ($srcName === 'ig' || $srcName === 'instagram') {
                $friendly = 'Instagram';
            } elseif ($srcName === 'tiktok') {
                $friendly = 'TikTok';
            } elseif ($srcName === 'wa' || $srcName === 'whatsapp') {
                $friendly = 'WhatsApp';
            } elseif ($srcName === 'kiosk') {
                $friendly = 'Walk-In Kiosk';
            } elseif ($srcName === 'manual') {
                $friendly = 'Receptionist Direct';
            } else {
                $friendly = ucfirst($srcName) . ' (Direct)';
            }

            $sources[] = [
                'source' => $friendly,
                'raw_source' => $srcName,
                'count' => $sc->total,
                'percentage' => $totalAcqs > 0 ? round(($sc->total / $totalAcqs) * 100, 1) : 0
            ];
        }

        // 6. Complete bookings list loaded with customer details for frontend reporting and export
        $bookingsList = Booking::with(['customer', 'outlet', 'stylist'])
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'kpis' => [
                'bookings_today' => $bookingsToday,
                'revenue_today' => $revenueToday,
                'revenue_month' => $revenueMonth,
                'no_show_rate' => $noShowRate,
                'customer_retention' => $retentionRate
            ],
            'popular_services' => $popularServices,
            'top_stylists' => $topStylists,
            'booking_trend' => $bookingTrend,
            'acquisition_sources' => $sources,
            'bookings' => $bookingsList
        ]);
    }

    public function export(Request $request)
    {
        $type = $request->query('type', 'revenue');
        
        // Return a mock Excel download simulation response
        return response()->json([
            'success' => true,
            'message' => 'Laporan ' . ucfirst($type) . ' berhasil diekspor!',
            'download_url' => 'https://example.com/downloads/report_' . $type . '_' . now()->format('Ymd') . '.xlsx'
        ]);
    }
}
