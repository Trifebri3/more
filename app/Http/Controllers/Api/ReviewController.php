<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Customer;
use App\Models\Stylist;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['customer:id,full_name', 'stylist:id,name'])
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
            
        return response()->json($reviews);
    }

    public function store(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'stylist_id' => 'nullable|exists:stylists,id'
        ]);

        // Find customer by email OR phone/whatsapp number
        $customer = Customer::where('email', $request->identifier)
            ->orWhere('whatsapp', $request->identifier)
            ->orWhere('phone', $request->identifier)
            ->first();

        if (!$customer) {
            return response()->json([
                'message' => 'Maaf, Email atau nomor WhatsApp Anda belum terdaftar sebagai pelanggan kami. Sesi booking diperlukan terlebih dahulu.'
            ], 403);
        }

        // Create the review
        $review = Review::create([
            'customer_id' => $customer->id,
            'stylist_id' => $request->stylist_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // Calculate and update stylist's average rating dynamically
        if ($request->stylist_id) {
            $stylist = Stylist::find($request->stylist_id);
            if ($stylist) {
                $avg = Review::where('stylist_id', $stylist->id)->avg('rating');
                $stylist->rating = round($avg, 2);
                $stylist->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas ulasan Anda! Ulasan Anda berhasil disimpan.',
            'review' => $review
        ], 201);
    }
}
