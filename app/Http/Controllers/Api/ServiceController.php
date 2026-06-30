<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        // Return services grouped by category or simple list with category
        $services = Service::with('category')->where('is_active', true)->get();
        $categories = ServiceCategory::with(['services' => function($q) {
            $q->where('is_active', true);
        }])->get();

        return response()->json([
            'services' => $services,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:5',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($request->name);
        $service = Service::create($validated);

        return response()->json($service, 201);
    }

    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'sometimes|required|exists:service_categories,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'sometimes|required|integer|min:5',
            'price' => 'sometimes|required|numeric|min:0',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $service->update($validated);

        return response()->json($service);
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json(['message' => 'Service deleted successfully']);
    }
}
