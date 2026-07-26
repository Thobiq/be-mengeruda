<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismUmkm;
use Illuminate\Http\Request;

class TourismUmkmController extends Controller
{
    /**
     * GET /api/tourism/umkms
     */
    public function index()
    {
        $umkms = TourismUmkm::with('products')->orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $umkms,
        ]);
    }

    /**
     * GET /api/tourism/umkms/{id}
     */
    public function show($id)
    {
        $umkm = TourismUmkm::with('products')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $umkm,
        ]);
    }

    /**
     * POST /api/tourism/umkms
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gmaps' => 'nullable|string|max:255',
            'wa' => 'nullable|string|max:50',
            'ig' => 'nullable|string|max:100',
            'fb' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'logo' => 'nullable',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tourism/umkms', 'public');
            $logoPath = asset('storage/' . $path);
        } elseif (is_string($request->input('logo'))) {
            $logoPath = $request->input('logo');
        }

        $umkm = TourismUmkm::create([
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'gmaps' => $validated['gmaps'] ?? null,
            'wa' => $validated['wa'] ?? null,
            'ig' => $validated['ig'] ?? null,
            'fb' => $validated['fb'] ?? null,
            'tiktok' => $validated['tiktok'] ?? null,
            'logo' => $logoPath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data UMKM berhasil ditambahkan',
            'data' => $umkm->load('products'),
        ], 201);
    }

    /**
     * PUT/PATCH /api/tourism/umkms/{id}
     */
    public function update(Request $request, $id)
    {
        $umkm = TourismUmkm::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:255',
            'gmaps' => 'nullable|string|max:255',
            'wa' => 'nullable|string|max:50',
            'ig' => 'nullable|string|max:100',
            'fb' => 'nullable|string|max:100',
            'tiktok' => 'nullable|string|max:100',
            'logo' => 'nullable',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tourism/umkms', 'public');
            $umkm->logo = asset('storage/' . $path);
        } elseif ($request->has('logo') && is_string($request->input('logo'))) {
            $umkm->logo = $request->input('logo');
        }

        $umkm->fill(collect($validated)->except('logo')->toArray());
        $umkm->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data UMKM berhasil diperbarui',
            'data' => $umkm->load('products'),
        ]);
    }

    /**
     * DELETE /api/tourism/umkms/{id}
     */
    public function destroy($id)
    {
        $umkm = TourismUmkm::findOrFail($id);
        $umkm->delete(); // produk akan otomatis terhapus karena cascadeOnDelete

        return response()->json([
            'status' => 'success',
            'message' => 'Data UMKM berhasil dihapus',
        ]);
    }
}
