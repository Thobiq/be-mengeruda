<?php

namespace App\Http\Controllers\Api\Tourism;

use App\Http\Controllers\Controller;
use App\Models\TourismEvent;
use Illuminate\Http\Request;

class TourismEventController extends Controller
{
    /**
     * GET /api/tourism/events
     */
    public function index()
    {
        $events = TourismEvent::orderBy('date', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $events,
        ]);
    }

    /**
     * GET /api/tourism/events/{id}
     */
    public function show($id)
    {
        $event = TourismEvent::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $event,
        ]);
    }

    /**
     * POST /api/tourism/events
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/events', 'public');
            $imagePath = asset('storage/' . $path);
        } elseif (is_string($request->input('image'))) {
            $imagePath = $request->input('image');
        }

        $event = TourismEvent::create([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'location' => $validated['location'] ?? null,
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? 'Akan Datang',
            'image' => $imagePath,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda kegiatan berhasil ditambahkan',
            'data' => $event,
        ], 201);
    }

    /**
     * PUT/PATCH /api/tourism/events/{id}
     */
    public function update(Request $request, $id)
    {
        $event = TourismEvent::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tourism/events', 'public');
            $event->image = asset('storage/' . $path);
        } elseif ($request->has('image') && is_string($request->input('image'))) {
            $event->image = $request->input('image');
        }

        $event->fill(collect($validated)->except('image')->toArray());
        $event->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda kegiatan berhasil diperbarui',
            'data' => $event,
        ]);
    }

    /**
     * DELETE /api/tourism/events/{id}
     */
    public function destroy($id)
    {
        $event = TourismEvent::findOrFail($id);
        $event->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda kegiatan berhasil dihapus',
        ]);
    }
}
