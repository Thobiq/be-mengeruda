<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Demographic;

class DemographicController extends Controller
{
    /**
     * Get the demographic data.
     * Since there is only one record, we always fetch the first one.
     */
    public function index()
    {
        $data = Demographic::first();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Store or update the demographic data.
     */
    public function storeOrUpdate(Request $request)
    {
        // Because there's only one record, we try to find the first one or create a new instance
        $demographic = Demographic::first() ?? new Demographic();

        // Fill the model with all request data
        // Since we defined $fillable in the model, only those fields will be updated
        $demographic->fill($request->all());
        $demographic->save();

        return response()->json([
            'success' => true,
            'message' => 'Data demografi berhasil disimpan.',
            'data' => $demographic
        ]);
    }
}
