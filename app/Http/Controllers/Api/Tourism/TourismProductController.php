<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismProduct;
use App\Models\TourismUmkm;
use Illuminate\Http\Request;

class TourismProductController extends Controller
{
    /**
     * GET /api/tourism/products
     * Atau GET /api/tourism/umkms/{umkmId}/products
     */
    public function index($umkmId = null)
    {
        $query = TourismProduct::with('umkm')->orderBy('id', 'desc');
        if ($umkmId) {
            $query->where('tourism_umkm_id', $umkmId);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    /**
     * GET /api/tourism/products/{id}
     */
    public function show($id)
    {
        $product = TourismProduct::with('umkm')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $product,
        ]);
    }

    /**
     * POST /api/tourism/products
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tourism_umkm_id' => 'required|exists:tourism_umkms,id',
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'images' => 'nullable',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('tourism/products', 'public');
                $images[] = asset('storage/' . $path);
            }
        } elseif ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/products', 'public');
            $images[] = asset('storage/' . $path);
        } elseif (is_array($request->input('images'))) {
            $images = $request->input('images');
        } elseif (is_string($request->input('images'))) {
            $images = [$request->input('images')];
        }

        $product = TourismProduct::create([
            'tourism_umkm_id' => $validated['tourism_umkm_id'],
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'Makanan',
            'price' => $validated['price'] ?? null,
            'description' => $validated['description'] ?? null,
            'images' => $images,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product->load('umkm'),
        ], 201);
    }

    /**
     * PUT/PATCH /api/tourism/products/{id}
     */
    public function update(Request $request, $id)
    {
        $product = TourismProduct::findOrFail($id);

        $validated = $request->validate([
            'tourism_umkm_id' => 'sometimes|required|exists:tourism_umkms,id',
            'name' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:100',
            'price' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'images' => 'nullable',
        ]);

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store('tourism/products', 'public');
                $images[] = asset('storage/' . $path);
            }
            $product->images = $images;
        } elseif ($request->has('images')) {
            $inputImg = $request->input('images');
            $product->images = is_array($inputImg) ? $inputImg : (is_string($inputImg) ? [$inputImg] : $product->images);
        }

        $product->fill(collect($validated)->except('images')->toArray());
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil diperbarui',
            'data' => $product->load('umkm'),
        ]);
    }

    /**
     * DELETE /api/tourism/products/{id}
     */
    public function destroy($id)
    {
        $product = TourismProduct::findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus',
        ]);
    }
}
