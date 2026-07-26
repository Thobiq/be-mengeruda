<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismNews;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourismNewsController extends Controller
{
    /**
     * GET /api/tourism/news
     */
    public function index()
    {
        $news = TourismNews::orderBy('date', 'desc')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ]);
    }

    /**
     * GET /api/tourism/news/{id_or_slug}
     */
    public function show($idOrSlug)
    {
        $news = TourismNews::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $news,
        ]);
    }

    /**
     * POST /api/tourism/news
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tourism_news,slug',
            'date' => 'required|date',
            'author' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        // Ensure unique slug
        if (TourismNews::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . time();
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/news', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif (is_string($request->input('image'))) {
            $imagePath = $request->input('image');
        }

        $news = TourismNews::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'date' => $validated['date'],
            'author' => $validated['author'] ?? 'Admin Desa',
            'content' => $validated['content'] ?? null,
            'status' => $validated['status'] ?? 'Diterbitkan',
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berita wisata berhasil ditambahkan',
            'data' => $news,
        ], 201);
    }

    /**
     * PUT/PATCH /api/tourism/news/{id}
     */
    public function update(Request $request, $id)
    {
        $news = TourismNews::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tourism_news,slug,' . $id,
            'date' => 'nullable|date',
            'author' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable',
        ]);

        if (!empty($validated['slug'])) {
            $news->slug = Str::slug($validated['slug']);
        } elseif (isset($validated['title']) && $validated['title'] !== $news->title) {
            $slug = Str::slug($validated['title']);
            if (TourismNews::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug . '-' . time();
            }
            $news->slug = $slug;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/news', 'public');
            $news->image = asset('storage/' . $path);
        } elseif ($request->has('image') && is_string($request->input('image'))) {
            $news->image = $request->input('image');
        }

        $news->fill(collect($validated)->except(['image', 'slug'])->toArray());
        $news->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Berita wisata berhasil diperbarui',
            'data' => $news,
        ]);
    }

    /**
     * DELETE /api/tourism/news/{id}
     */
    public function destroy($id)
    {
        $news = TourismNews::findOrFail($id);
        $news->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berita wisata berhasil dihapus',
        ]);
    }
}
