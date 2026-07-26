<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismProfile;
use Illuminate\Http\Request;

class TourismProfileController extends Controller
{
    /**
     * GET /api/tourism/profile
     */
    public function show()
    {
        $profile = TourismProfile::first();

        if (!$profile) {
            $profile = TourismProfile::create([
                'nama_desa' => 'Desa Mengeruda',
                'deskripsi_singkat' => 'Menjelajahi keajaiban geo-wisata dan kekayaan budaya peninggalan leluhur di tanah Ngada.',
                'sejarah' => 'Mengeruda merupakan kawasan geo-wisata yang terkenal dengan kolam pemandian air panas alami dan penemuan batuan purba.',
                'telepon' => '081234567890',
                'email' => 'pariwisata@mengeruda.id',
                'alamat' => 'Kantor Desa Mengeruda, Kec. Soa, Kab. Ngada',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $profile,
        ]);
    }

    /**
     * PUT/PATCH /api/tourism/profile
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_desa' => 'nullable|string|max:255',
            'deskripsi_singkat' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'alamat' => 'nullable|string|max:255',
        ]);

        $profile = TourismProfile::first();
        if (!$profile) {
            $profile = new TourismProfile();
        }

        $profile->fill($validated);
        $profile->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Profil wisata desa berhasil diperbarui',
            'data' => $profile,
        ]);
    }
}
