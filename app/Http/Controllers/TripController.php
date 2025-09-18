<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class TripController extends Controller
{
    /**
     * Display a listing of trips
     */
    public function index(Request $request): View
    {
        $query = Trip::query();

        // Apply status filter
        $filter = $request->get('filter', 'all');
        $query->byStatus($filter);

        // Apply search
        $search = $request->get('search');
        $query->search($search);

        $trips = $query->orderBy('created_at', 'desc')->get();

        // Get header title based on filter
        $headerTitles = [
            'all' => 'Todos los Viajes',
            'draft' => 'Viajes en Diseño',
            'sent' => 'Propuestas Enviadas',
            'approved' => 'Viajes Aprobados',
            'completed' => 'Viajes Pasados'
        ];

        return view('trips.index', [
            'trips' => $trips,
            'activeTab' => $filter,
            'headerTitle' => $headerTitles[$filter] ?? 'Todos los Viajes'
        ]);
    }

    /**
     * Show the form for creating a new trip
     */
    public function create(): View
    {
        return view('trips.editor');
    }

    /**
     * Store a newly created trip
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'travelers' => 'required|integer|min:1',
            'destination' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'items_data' => 'nullable|array'
        ]);

        $validated['status'] = Trip::STATUS_DRAFT;

        $trip = Trip::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Viaje creado exitosamente',
            'trip' => $trip
        ]);
    }

    /**
     * Display the specified trip
     */
    public function show(Trip $trip): View
    {
        return view('trips.preview', [
            'trip' => $trip
        ]);
    }

    /**
     * Show the form for editing the specified trip
     */
    public function edit(Trip $trip): View
    {
        return view('trips.editor', [
            'trip' => $trip
        ]);
    }

    /**
     * Update the specified trip
     */
    public function update(Request $request, Trip $trip): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'travelers' => 'required|integer|min:1',
            'destination' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'items_data' => 'nullable|array'
        ]);

        $trip->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Viaje actualizado exitosamente',
            'trip' => $trip
        ]);
    }

    /**
     * Remove the specified trip
     */
    public function destroy(Trip $trip): JsonResponse
    {
        $trip->delete();

        return response()->json([
            'success' => true,
            'message' => 'Viaje eliminado exitosamente'
        ]);
    }

    /**
     * Update trip status
     */
    public function updateStatus(Request $request, Trip $trip): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:draft,sent,approved,completed'
        ]);

        $trip->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del viaje actualizado exitosamente',
            'trip' => $trip
        ]);
    }

    /**
     * Preview trip (for temporary trips)
     */
    public function preview(Request $request): View
    {
        $tripId = $request->route('trip');

        if ($tripId === 'temp') {
            // Handle temporary trip preview
            $trip = new Trip([
                'title' => 'Vista Previa del Viaje',
                'start_date' => now(),
                'end_date' => now()->addDays(3),
                'travelers' => 2,
                'destination' => 'Destino de ejemplo',
                'status' => Trip::STATUS_DRAFT,
                'items_data' => []
            ]);
        } else {
            $trip = Trip::findOrFail($tripId);
        }

        return view('trips.preview', [
            'trip' => $trip
        ]);
    }

    /**
     * Duplicate trip
     */
    public function duplicate(Trip $trip): JsonResponse
    {
        $newTrip = $trip->replicate();
        $newTrip->title = $trip->title . ' (Copia)';
        $newTrip->status = Trip::STATUS_DRAFT;
        $newTrip->save();

        return response()->json([
            'success' => true,
            'message' => 'Viaje duplicado exitosamente',
            'trip' => $newTrip
        ]);
    }

    /**
     * Bulk delete trips
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trip_ids' => 'required|array',
            'trip_ids.*' => 'integer|exists:trips,id'
        ]);

        $deletedCount = Trip::whereIn('id', $validated['trip_ids'])->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} viajes eliminados exitosamente"
        ]);
    }

    /**
     * Bulk duplicate trips
     */
    public function bulkDuplicate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trip_ids' => 'required|array',
            'trip_ids.*' => 'integer|exists:trips,id'
        ]);

        $trips = Trip::whereIn('id', $validated['trip_ids'])->get();
        $duplicatedCount = 0;

        foreach ($trips as $trip) {
            $newTrip = $trip->replicate();
            $newTrip->title = $trip->title . ' (Copia)';
            $newTrip->status = Trip::STATUS_DRAFT;
            $newTrip->save();
            $duplicatedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$duplicatedCount} viajes duplicados exitosamente"
        ]);
    }
}
