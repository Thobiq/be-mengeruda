<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismAttraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourismAttractionController extends Controller
{
    /**
     * GET /api/tourism/attractions
     */
    public function index()
    {
        $attractions = TourismAttraction::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $attractions,
        ]);
    }

    /**
     * GET /api/tourism/attractions/{id}
     */
    public function show($id)
    {
        $attraction = TourismAttraction::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $attraction,
        ]);
    }

    /**
     * POST /api/tourism/attractions
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/attractions', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif (is_string($request->input('image'))) {
            $imagePath = $request->input('image');
        }

        $attraction = TourismAttraction::create([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'price' => $validated['price'] ?? 'Gratis',
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tempat wisata berhasil ditambahkan',
            'data' => $attraction,
        ], 201);
    }

    /**
     * PUT/PATCH /api/tourism/attractions/{id}
     */
    public function update(Request $request, $id)
    {
        $attraction = TourismAttraction::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:255',
            'price' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/attractions', 'public');
            $attraction->image = asset('storage/' . $path);
        } elseif ($request->has('image') && is_string($request->input('image'))) {
            $attraction->image = $request->input('image');
        }

        $attraction->fill(collect($validated)->except('image')->toArray());
        $attraction->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tempat wisata berhasil diperbarui',
            'data' => $attraction,
        ]);
    }

    /**
     * DELETE /api/tourism/attractions/{id}
     */
    public function destroy($id)
    {
        $attraction = TourismAttraction::findOrFail($id);
        $attraction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tempat wisata berhasil dihapus',
        ]);
    }
}
