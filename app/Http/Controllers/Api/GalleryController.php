<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $galleries = Gallery::latest()->get();

        $galleries->transform(function ($item) {
            if ($item->image_path) {
                if (str_starts_with($item->image_path, 'http://') || str_starts_with($item->image_path, 'https://')) {
                    $item->image_url = $item->image_path;
                } else {
                    $clean = ltrim(str_replace('/storage/', '', $item->image_path), '/');
                    $item->image_url = asset('storage/' . $clean);
                }
            }
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $galleries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('galleries', 'public');
            
            $gallery = Gallery::create([
                'title' => $request->title,
                'image_path' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil diunggah',
                'data' => $gallery
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunggah gambar'
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return response()->json([
            'success' => true,
            'data' => $gallery
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = ['title' => $request->title];

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if (Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            
            // Simpan gambar baru
            $path = $request->file('image')->store('galleries', 'public');
            $data['image_path'] = $path;
        }

        $gallery->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Detail gambar berhasil diperbarui',
            'data' => $gallery
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        if (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        
        $gallery->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus'
        ]);
    }
}
