<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerangkatDesa;

class PerangkatDesaController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        // Untuk d3-org-chart, format data flat array dengan relasi id dan parent_id adalah yang terbaik.
        $perangkat = PerangkatDesa::orderBy('urutan')->get();
        return response()->json([
            'success' => true,
            'data' => $perangkat
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:perangkat_desas,id',
            'urutan' => 'nullable|integer',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('perangkat', 'public');
            $validated['foto'] = $path;
        }

        $perangkat = PerangkatDesa::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data perangkat desa berhasil ditambahkan',
            'data' => $perangkat
        ]);
    }

    public function update(Request $request, $id)
    {
        $perangkat = PerangkatDesa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'parent_id' => 'nullable|exists:perangkat_desas,id',
            'urutan' => 'nullable|integer',
        ]);

        // Mencegah loop hirarki (parent_id tidak boleh diri sendiri atau bawahannya)
        if ($validated['parent_id'] == $id) {
            return response()->json([
                'success' => false,
                'message' => 'Atasan tidak boleh diri sendiri'
            ], 422);
        }

        if ($request->hasFile('foto')) {
            if ($perangkat->foto) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($perangkat->foto);
            }
            $path = $request->file('foto')->store('perangkat', 'public');
            $validated['foto'] = $path;
        }

        $perangkat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data perangkat desa berhasil diperbarui',
            'data' => $perangkat
        ]);
    }

    public function destroy($id)
    {
        $perangkat = PerangkatDesa::findOrFail($id);
        
        if ($perangkat->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($perangkat->foto);
        }

        $perangkat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data perangkat desa berhasil dihapus'
        ]);
    }
}
