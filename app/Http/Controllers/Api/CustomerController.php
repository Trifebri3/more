<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Booking;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function profile(Request $request)
    {
        $customer = Customer::where('user_id', $request->user()->id)->first();
        
        if (!$customer) {
            return response()->json(['message' => 'Profil pelanggan tidak ditemukan.'], 404);
        }

        return response()->json($customer);
    }

    public function update(Request $request)
    {
        $customer = Customer::where('user_id', $request->user()->id)->firstOrFail();

        $validated = $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|nullable|email|max:255',
            'date_of_birth' => 'sometimes|nullable|date',
            'gender' => 'sometimes|nullable|in:male,female',
        ]);

        $customer->update($validated);

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

    public function bookings(Request $request)
    {
        $customer = Customer::where('user_id', $request->user()->id)->firstOrFail();

        $bookings = Booking::with(['outlet', 'stylist'])
            ->where('customer_id', $customer->id)
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        return response()->json($bookings);
    }

    public function check(Request $request)
    {
        $request->validate([
            'whatsapp' => 'required|string',
        ]);

        $customer = Customer::where('whatsapp', $request->whatsapp)->first();

        if ($customer) {
            return response()->json([
                'exists' => true,
                'customer' => $customer
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }

    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'whatsapp' => 'required|string|max:20|unique:customers,whatsapp',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'membership_status' => 'nullable|string|in:regular,silver,gold,vip',
        ]);

        $customer = Customer::create($validated);
        return response()->json($customer, 201);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully']);
    }
}
