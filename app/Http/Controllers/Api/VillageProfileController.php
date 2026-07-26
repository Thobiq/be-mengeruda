<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VillageProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VillageProfileController extends Controller
{
    /**
     * Get the single village profile data.
     */
    public function index()
    {
        $profile = VillageProfile::first();
        
        // If logo exists, generate the full URL
        if ($profile && $profile->logo) {
            $profile->logo_url = asset('storage/' . $profile->logo);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    /**
     * Store or update ONLY the village info (Informasi Desa).
     */
    public function updateInfo(Request $request)
    {
        $validatedData = $request->validate([
            'nama_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'telp' => 'nullable|string|max:50',
            'email' => 'nullable|string|max:255',
            'alamat' => 'required|string',
            'logo' => 'nullable|file|max:20480',
        ]);

        $profile = VillageProfile::first() ?? new VillageProfile();

        // Handle file upload for logo
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($profile->exists && $profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            
            // Store new logo
            $path = $request->file('logo')->store('logos', 'public');
            $validatedData['logo'] = $path;
        }

        $profile->fill($validatedData);
        $profile->save();

        if ($profile->logo) {
            $profile->logo_url = asset('storage/' . $profile->logo);
        }

        return response()->json([
            'success' => true,
            'message' => 'Informasi Desa berhasil disimpan.',
            'data' => $profile
        ]);
    }

    /**
     * Store or update ONLY the village narrative (Narasi Desa).
     */
    public function updateNarasi(Request $request)
    {
        $validatedData = $request->validate([
            'tentang_desa' => 'nullable|string',
            'sejarah_desa' => 'nullable|string',
            'visi_desa' => 'nullable|string',
            'misi_desa' => 'nullable|string',
        ]);

        // If the profile doesn't exist yet, we still need the required fields from info to be filled eventually,
        // but for now, we can create a record with placeholder values, OR assume it should only update.
        // To be safe, we'll create it with empty strings for required fields if it doesn't exist, 
        // though it's better to require the user to fill Info first.
        $profile = VillageProfile::first();
        
        if (!$profile) {
            // Create a temporary record with default empty strings for required fields 
            // so database constraints don't fail, or just return an error.
            // Let's create it with placeholders.
            $profile = new VillageProfile([
                'nama_desa' => '',
                'kecamatan' => '',
                'kabupaten' => '',
                'provinsi' => '',
                'alamat' => '',
            ]);
        }

        $profile->fill($validatedData);
        $profile->save();

        if ($profile->logo) {
            $profile->logo_url = asset('storage/' . $profile->logo);
        }

        return response()->json([
            'success' => true,
            'message' => 'Narasi Desa berhasil disimpan.',
            'data' => $profile
        ]);
    }
}
