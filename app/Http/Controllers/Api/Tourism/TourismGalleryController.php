<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismGallery;
use Illuminate\Http\Request;

class TourismGalleryController extends Controller
{
    /**
     * GET /api/tourism/galleries
     */
    public function index(Request $request)
    {
        $query = TourismGallery::orderBy('id', 'desc');

        if ($request->has('category') && $request->query('category') !== 'Semua') {
            $query->where('category', $request->query('category'));
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * GET /api/tourism/galleries/{id}
     */
    public function show($id)
    {
        $gallery = TourismGallery::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $gallery,
        ]);
    }

    /**
     * POST /api/tourism/galleries
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/galleries', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif (is_string($request->input('image'))) {
            $imagePath = $request->input('image');
        }

        $gallery = TourismGallery::create([
            'title' => $validated['title'],
            'category' => $validated['category'] ?? 'Alam',
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Foto galeri berhasil ditambahkan',
            'data' => $gallery,
        ], 201);
    }

    /**
     * PUT/PATCH /api/tourism/galleries/{id}
     */
    public function update(Request $request, $id)
    {
        $gallery = TourismGallery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/galleries', 'public');
            $gallery->image = asset('storage/' . $path);
        } elseif ($request->has('image') && is_string($request->input('image'))) {
            $gallery->image = $request->input('image');
        }

        $gallery->fill(collect($validated)->except('image')->toArray());
        $gallery->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Foto galeri berhasil diperbarui',
            'data' => $gallery,
        ]);
    }

    /**
     * DELETE /api/tourism/galleries/{id}
     */
    public function destroy($id)
    {
        $gallery = TourismGallery::findOrFail($id);
        $gallery->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Foto galeri berhasil dihapus',
        ]);
    }
}
