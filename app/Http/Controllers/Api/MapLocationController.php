<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MapLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MapLocationController extends Controller
{
    public function index()
    {
        $locations = MapLocation::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    public function show($id)
    {
        $location = MapLocation::find($id);
        if ($location) {
            return response()->json([
                'success' => true,
                'data' => $location
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Location not found'
        ], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('maps', 'public');
            $validated['thumbnail'] = '/storage/' . $path;
        }

        $location = MapLocation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil ditambahkan',
            'data' => $location
        ]);
    }

    public function update(Request $request, $id)
    {
        $location = MapLocation::find($id);
        if (!$location) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($location->thumbnail) {
                $oldPath = str_replace('/storage/', '', $location->thumbnail);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('thumbnail')->store('maps', 'public');
            $validated['thumbnail'] = '/storage/' . $path;
        }

        $location->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui',
            'data' => $location
        ]);
    }

    public function importGeoJson(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        $content = file_get_contents($file->getRealPath());
        $json = json_decode($content, true);

        if (!$json || !isset($json['features'])) {
            return response()->json([
                'success' => false,
                'message' => 'Format file GeoJSON tidak valid.'
            ], 400);
        }

        $importedCount = 0;

        foreach ($json['features'] as $feature) {
            // Kita hanya memproses tipe Point untuk sementara
            if (isset($feature['geometry']['type']) && $feature['geometry']['type'] === 'Point') {
                $coords = $feature['geometry']['coordinates'];
                // GeoJSON standar: [Longitude, Latitude]
                $longitude = $coords[0];
                $latitude = $coords[1];

                $name = $feature['properties']['Name'] ?? 'Lokasi Tanpa Nama';
                
                MapLocation::create([
                    'name' => $name,
                    'category' => 'Fasilitas Umum', // Kategori default
                    'description' => null,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'thumbnail' => null
                ]);

                $importedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$importedCount lokasi berhasil diimpor dari GeoJSON."
        ]);
    }

    public function destroy($id)
    {
        $location = MapLocation::find($id);
        if ($location) {
            if ($location->thumbnail) {
                $oldPath = str_replace('/storage/', '', $location->thumbnail);
                Storage::disk('public')->delete($oldPath);
            }
            $location->delete();
            return response()->json(['success' => true, 'message' => 'Deleted']);
        }
        return response()->json(['success' => false, 'message' => 'Not found'], 404);
    }
}
