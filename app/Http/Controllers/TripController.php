<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SendTripLink;

class TripController extends Controller
{
    /**
     * Display a listing of trips
     */
    public function index(Request $request): View
    {
        $query = Trip::with('user')->where('user_id', Auth::id());

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
        return view('trips.create');
    }

    /**
     * Store a newly created trip
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'travelers' => 'nullable|integer|min:1',
            'destination' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'items_data' => 'nullable|array'
        ]);

        // Set default start and end dates
        $validated['start_date'] = now();
        $validated['end_date'] = now();

        // Smart duplicate handling: update existing trips instead of rejecting
        return DB::transaction(function() use ($validated) {
            // Get current user ID once
            $userId = Auth::id();

            // Check for existing trips with the same title (regardless of date)
            $existingTrip = Trip::where('user_id', $userId)
                ->where('title', $validated['title'])
                ->first();

            if ($existingTrip) {
                // Update the existing trip instead of creating a duplicate
                $existingTrip->update([
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'travelers' => $validated['travelers'] ?? 1,
                    'destination' => $validated['destination'] ?? '',
                    'summary' => $validated['summary'] ?? '',
                    'items_data' => $validated['items_data'] ?? []
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Viaje actualizado exitosamente',
                    'trip' => $existingTrip->fresh(),
                    'action' => 'updated' // Indicate this was an update, not a new creation
                ]);
            }

            // Count trips with similar title (allow some variation, but limit excessive creation)
            $similarTrips = Trip::where('user_id', $userId)
                ->where('title', 'LIKE', '%' . trim($validated['title']) . '%')
                ->where('created_at', '>=', now()->subDay())
                ->count();

            if ($similarTrips >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Has creado demasiados viajes similares hoy. Espera un poco o cambia el título.'
                ], 422);
            }

            // Set default values for optional fields
            $validated['travelers'] = $validated['travelers'] ?? 1;
            $validated['status'] = Trip::STATUS_DRAFT;
            $validated['user_id'] = $userId;

            // Create the trip within the transaction
            $trip = Trip::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Viaje creado exitosamente',
                'trip' => $trip,
                'action' => 'created' // Indicate this was a new creation
            ]);
        });
    }

    /**
     * Display the specified trip
     */
    public function show(Trip $trip): View
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este viaje.');
        }

        return view('trips.preview', [
            'trip' => $trip->load('user'),
            'isPublicPreview' => false
        ]);
    }

    /**
     * Show the form for editing the specified trip
     */
    public function edit(Trip $trip): View
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este viaje.');
        }

        return view('trips.edit', [
            'trip' => $trip->load(['persons', 'documents'])
        ]);
    }

    /**
     * Update the specified trip
     */
    public function update(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para actualizar este viaje.'
            ], 403);
        }

        // Temporary debug logging to inspect incoming payload when saving from editor
        try {
            Log::info('TripController@update called', [
                'trip_id' => $trip->id,
                'request_all' => $request->all(),
                'user_id' => Auth::id()
            ]);
        } catch (\Throwable $e) {
            // Don't break the request if logging fails
            Log::error('Failed logging TripController@update payload: ' . $e->getMessage());
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'travelers' => 'nullable|integer|min:1',
            'destination' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'items_data' => 'nullable|array'
        ]);

        // Log validated payload for debugging
        try {
            Log::info('TripController@update validated data', [
                'trip_id' => $trip->id,
                'validated' => $validated
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed logging TripController@update validated payload: ' . $e->getMessage());
        }
        // Set default values for optional fields
        $validated['travelers'] = $validated['travelers'] ?? 1;

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
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este viaje.'
            ], 403);
        }

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
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para actualizar este viaje.'
            ], 403);
        }

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
     * Preview trip (public access for clients)
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
            // Load existing trip with user relationship
            $trip = Trip::with('user')->findOrFail($tripId);

            // For public preview, ensure the trip is in a shareable state
            // Allow preview for drafts if the user is authenticated and owns the trip
            $isOwner = Auth::check() && $trip->user_id === Auth::id();
            if (!$isOwner && !in_array($trip->status, [Trip::STATUS_SENT, Trip::STATUS_APPROVED, Trip::STATUS_COMPLETED])) {
                // Only allow preview for trips that have been sent or approved, or for owners in draft
                abort(404, 'Vista previa no disponible para este viaje.');
            }
        }

        return view('trips.preview', [
            'trip' => $trip,
            'isPublicPreview' => !Auth::check()
        ]);
    }

    /**
     * Share trip preview (public access via token)
     */
    public function share(Request $request): View
    {
        $token = $request->route('token');

        $trip = Trip::findByShareToken($token);

        if (!$trip) {
            abort(404, 'Enlace de compartición no válido o expirado.');
        }

        // Only allow sharing for trips that have been sent or approved
        if (!in_array($trip->status, [Trip::STATUS_SENT, Trip::STATUS_APPROVED, Trip::STATUS_COMPLETED])) {
            abort(404, 'Este viaje no está disponible para compartir.');
        }

        return view('trips.preview', [
            'trip' => $trip->load('user'),
            'isPublicPreview' => true,
            'isSharedPreview' => true
        ]);
    }

    /**
     * Duplicate trip
     */
    public function duplicate(Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para duplicar este viaje.'
            ], 403);
        }

        $newTrip = $trip->replicate();
        $newTrip->title = $trip->title . ' (Copia)';
        $newTrip->status = Trip::STATUS_DRAFT;
        $newTrip->user_id = Auth::id(); // Ensure the duplicate belongs to the current user
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

        // Ensure all trips belong to the authenticated user
        $userTrips = Trip::whereIn('id', $validated['trip_ids'])
                        ->where('user_id', Auth::id())
                        ->pluck('id')
                        ->toArray();

        if (count($userTrips) !== count($validated['trip_ids'])) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar algunos de los viajes seleccionados.'
            ], 403);
        }

        $deletedCount = Trip::whereIn('id', $userTrips)->delete();

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

         // Ensure all trips belong to the authenticated user
         $trips = Trip::whereIn('id', $validated['trip_ids'])
                     ->where('user_id', Auth::id())
                     ->get();

         if ($trips->count() !== count($validated['trip_ids'])) {
             return response()->json([
                 'success' => false,
                 'message' => 'No tienes permiso para duplicar algunos de los viajes seleccionados.'
             ], 403);
         }

         $duplicatedCount = 0;

         foreach ($trips as $trip) {
             $newTrip = $trip->replicate();
             $newTrip->title = $trip->title . ' (Copia)';
             $newTrip->status = Trip::STATUS_DRAFT;
             $newTrip->user_id = Auth::id(); // Ensure the duplicate belongs to the current user
             $newTrip->save();
             $duplicatedCount++;
         }

         return response()->json([
             'success' => true,
             'message' => "{$duplicatedCount} viajes duplicados exitosamente"
         ]);
     }

     /**
      * Generate share token for trip
      */
     public function generateShareToken(Trip $trip): JsonResponse
     {
         // Ensure the trip belongs to the authenticated user
         if ($trip->user_id !== Auth::id()) {
             return response()->json([
                 'success' => false,
                 'message' => 'No tienes permiso para compartir este viaje.'
             ], 403);
         }

         // Only allow sharing for trips that have been sent or approved
         if (!in_array($trip->status, [Trip::STATUS_SENT, Trip::STATUS_APPROVED, Trip::STATUS_COMPLETED])) {
             return response()->json([
                 'success' => false,
                 'message' => 'Solo puedes compartir viajes que han sido enviados o aprobados.'
             ], 400);
         }

         $shareUrl = $trip->getShareUrl();

         return response()->json([
             'success' => true,
             'message' => 'Enlace de compartición generado exitosamente',
             'share_url' => $shareUrl,
             'share_token' => $trip->share_token
         ]);
     }

     /**
      * Generate PDF for trip
      */
     public function generatePdf(Request $request, Trip $trip)
     {
         // Check if this is a shared access (via token)
         $token = $request->get('token');
         $isShared = $token && $trip->share_token === $token;

         // Ensure the trip belongs to the authenticated user or is shared
         $isOwner = Auth::check() && $trip->user_id === Auth::id();

         if (!$isOwner && !$isShared) {
             abort(403, 'No tienes permiso para descargar este viaje.');
         }

         // Only allow PDF generation for trips that have been sent or approved
         if (!in_array($trip->status, [Trip::STATUS_SENT, Trip::STATUS_APPROVED, Trip::STATUS_COMPLETED])) {
             abort(403, 'Solo puedes descargar viajes que han sido enviados o aprobados.');
         }

         $trip->load('user');

         $pdf = Pdf::loadView('trips.pdf', [
             'trip' => $trip,
             'isPublicPreview' => !$isOwner
         ]);

         $startDate = $trip->start_date ? $trip->start_date->format('d-m-Y') : 'sin-fecha';
         $filename = 'itinerario-' . Str::slug($trip->title) . '-' . $startDate . '.pdf';

         return $pdf->download($filename);
     }

     /**
      * Send trip link via email
      */
     public function sendEmail(Request $request, Trip $trip): JsonResponse
     {
         // Ensure the trip belongs to the authenticated user
         if ($trip->user_id !== Auth::id()) {
             return response()->json([
                 'success' => false,
                 'message' => 'No tienes permiso para enviar este viaje.'
             ], 403);
         }

         // Only allow sending for trips that have been sent or approved
         if (!in_array($trip->status, [Trip::STATUS_SENT, Trip::STATUS_APPROVED, Trip::STATUS_COMPLETED])) {
             return response()->json([
                 'success' => false,
                 'message' => 'Solo puedes enviar viajes que han sido enviados o aprobados.'
             ], 400);
         }

         $validated = $request->validate([
             'email' => 'required|email',
             'message' => 'nullable|string|max:1000'
         ]);

         try {
             Mail::to($validated['email'])->send(new SendTripLink($trip, $validated['message'] ?? null));

             return response()->json([
                 'success' => true,
                 'message' => 'El enlace del viaje ha sido enviado exitosamente.'
             ]);
         } catch (\Exception $e) {
             Log::error('Error sending trip email: ' . $e->getMessage());
             return response()->json([
                 'success' => false,
                 'message' => 'No se pudo enviar el correo. Por favor intenta de nuevo.'
             ], 500);
         }
     }
 }
