<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismProfile;
use App\Models\VillageProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $village = VillageProfile::first();
        if (!$profile->logo) {
            if ($village && $village->logo) {
                $profile->logo = $village->logo;
            } else {
                $files = Storage::disk('public')->files('logos');
                if (!empty($files)) {
                    $profile->logo = $files[0];
                }
            }
        }
        if ($profile->logo) {
            $profile->logo_url = str_starts_with($profile->logo, 'http') ? $profile->logo : asset('storage/' . ltrim(str_replace('/storage/', '', $profile->logo), '/'));
        }
        if ($profile->hero_image) {
            $profile->hero_image_url = str_starts_with($profile->hero_image, 'http') ? $profile->hero_image : asset('storage/' . ltrim(str_replace('/storage/', '', $profile->hero_image), '/'));
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
            'logo' => 'nullable|file|max:20480',
        ]);

        $profile = TourismProfile::first();
        if (!$profile) {
            $profile = new TourismProfile();
        }

        if ($request->hasFile('logo')) {
            if ($profile->exists && $profile->logo && !str_starts_with($profile->logo, 'http') && Storage::disk('public')->exists($profile->logo)) {
                Storage::disk('public')->delete($profile->logo);
            }
            $path = $request->file('logo')->store('tourism/logos', 'public');
            $validated['logo'] = $path;
        }

        $profile->fill($validated);
        $profile->save();

        if ($profile->logo) {
            $profile->logo_url = str_starts_with($profile->logo, 'http') ? $profile->logo : asset('storage/' . ltrim(str_replace('/storage/', '', $profile->logo), '/'));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profil wisata desa berhasil diperbarui',
            'data' => $profile,
        ]);
    }
}
