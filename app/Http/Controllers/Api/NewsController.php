<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Get list of all news
     */
    public function index()
    {
        $news = News::orderBy('created_at', 'desc')->get();
        
        $news->transform(function ($item) {
            if ($item->banner) {
                $item->banner_url = asset('storage/' . $item->banner);
            }
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Get a single news by slug
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)->first();
        
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        if ($news->banner) {
            $news->banner_url = asset('storage/' . $news->banner);
        }

        return response()->json([
            'success' => true,
            'data' => $news
        ]);
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:500',
            'slug' => 'nullable|string|max:500',
            'banner' => 'nullable|file|max:20480',
            'content' => 'nullable|string',
        ]);

        $slug = Str::slug($request->input('slug') ?: $request->input('judul', 'berita'));
        $originalSlug = $slug;
        $counter = 2;
        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $validatedData['slug'] = $slug;

        if (empty($validatedData['content'])) {
            $validatedData['content'] = ' ';
        }

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('news_banners', 'public');
            $validatedData['banner'] = $path;
        }

        $news = News::create($validatedData);

        if ($news->banner) {
            $news->banner_url = asset('storage/' . $news->banner);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil disimpan.',
            'data' => $news
        ]);
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, $id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $validatedData = $request->validate([
            'judul' => 'required|string|max:500',
            'slug' => 'nullable|string|max:500',
            'banner' => 'nullable|file|max:20480',
            'content' => 'nullable|string',
        ]);

        $slug = Str::slug($request->input('slug') ?: $request->input('judul', $news->slug));
        $originalSlug = $slug;
        $counter = 2;
        while (News::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        $validatedData['slug'] = $slug;

        if (empty($validatedData['content'])) {
            $validatedData['content'] = ' ';
        }

        if ($request->hasFile('banner')) {
            if ($news->banner && Storage::disk('public')->exists($news->banner)) {
                Storage::disk('public')->delete($news->banner);
            }
            $path = $request->file('banner')->store('news_banners', 'public');
            $validatedData['banner'] = $path;
        }

        $news->update($validatedData);

        if ($news->banner) {
            $news->banner_url = asset('storage/' . $news->banner);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diperbarui.',
            'data' => $news
        ]);
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy($id)
    {
        $news = News::find($id);
        if (!$news) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        if ($news->banner && Storage::disk('public')->exists($news->banner)) {
            Storage::disk('public')->delete($news->banner);
        }

        $news->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus.'
        ]);
    }
}
