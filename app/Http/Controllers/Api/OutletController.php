<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::all();
        return response()->json($outlets);
    }

    public function show($id)
    {
        $outlet = Outlet::with('stylists')->findOrFail($id);
        return response()->json($outlet);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image_url' => 'nullable|url',
            'rating' => 'nullable|numeric|between:0,5',
            'opening_hours' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);
        $outlet = Outlet::create($validated);

        return response()->json($outlet, 201);
    }

    public function update(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image_url' => 'nullable|url',
            'rating' => 'nullable|numeric|between:0,5',
            'opening_hours' => 'nullable|array',
            'description' => 'nullable|string',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        }

        $outlet->update($validated);

        return response()->json($outlet);
    }

    public function destroy($id)
    {
        $outlet = Outlet::findOrFail($id);
        $outlet->delete();

        return response()->json(['message' => 'Outlet deleted successfully']);
    }
}
