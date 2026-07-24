<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApbDesa;

class ApbDesaController extends Controller
{
    /**
     * Get list of all available years and their data (optional, but let's just return all APB)
     */
    public function index()
    {
        $apb = ApbDesa::orderBy('year', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $apb
        ]);
    }

    /**
     * Get APB data by year
     */
    public function show($year)
    {
        $apb = ApbDesa::where('year', $year)->first();

        if (!$apb) {
            return response()->json([
                'success' => false,
                'message' => 'Data APB tidak ditemukan untuk tahun tersebut',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $apb
        ]);
    }

    /**
     * Create or update APB data for a specific year
     */
    public function storeOrUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'year' => 'required|integer',
            'data' => 'required|array',
        ]);

        $apb = ApbDesa::updateOrCreate(
            ['year' => $validatedData['year']],
            ['data' => $validatedData['data']]
        );

        return response()->json([
            'success' => true,
            'message' => 'Data APB Desa berhasil disimpan.',
            'data' => $apb
        ]);
    }

    /**
     * Delete APB data by year
     */
    public function destroy($year)
    {
        $apb = ApbDesa::where('year', $year)->first();
        if ($apb) {
            $apb->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data APB Desa berhasil dihapus.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Data APB tidak ditemukan.'
        ], 404);
    }
}
