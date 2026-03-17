<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SendTripLink;
use App\Models\TripCollaborator;
use App\Mail\TripCollaborationInvite;

class TripController extends Controller
{
    /**
     * Display a listing of trips
     */
    public function index(Request $request): View
    {
        $userId = Auth::id();
        $filter = $request->get('filter', 'personal'); // Changed default to personal

        if ($filter === 'shared') {
            $query = Trip::whereHas('collaborators', function($q) use ($userId) {
                $q->where('user_id', $userId)->whereNotNull('accepted_at');
            })->with(['user', 'persons']);
        } else {
            $query = Trip::with(['user', 'persons'])->where('user_id', $userId);
        }

        // Calculate stats before filtering
        $allTripsQuery = Trip::where('user_id', $userId);
        $stats = [
            'total' => (clone $allTripsQuery)->count(),
            'draft' => (clone $allTripsQuery)->where('status', 'draft')->count(),
            'sent' => (clone $allTripsQuery)->where('status', 'sent')->count(),
            'reserved' => (clone $allTripsQuery)->where('status', 'reserved')->count(),
            'completed' => (clone $allTripsQuery)->where('status', 'completed')->count(),
            'discarded' => (clone $allTripsQuery)->where('status', 'discarded')->count(),
        ];

        // Apply status filter
        $status = $request->get('status', 'all');
        $query->byStatus($status);

        // Apply search
        $search = $request->get('search');
        $query->search($search);

        $trips = $query->orderBy('created_at', 'desc')->get();

        // Get header title based on filter
        $headerTitles = [
            'all' => 'Todos los Viajes',
            'draft' => 'Viajes en Diseño',
            'sent' => 'Propuestas Enviadas',
            'reserved' => 'Viajes Reservados',
            'completed' => 'Viajes Completados',
            'discarded' => 'Viajes Descartados'
        ];

        return view('trips.index', [
            'trips' => $trips,
            'activeTab' => $status,
            'activeMainTab' => $filter,
            'headerTitle' => $headerTitles[$status] ?? 'Todos los Viajes',
            'stats' => $stats
        ]);
    }


    /**
     * Show the new PRO editor for creating a new trip
     */
    public function createPro(): View
    {
        return view('trips.pro-editor', [
            'trip' => null
        ]);
    }

    /**
     * Store a quickly created PRO trip
     */
    public function storePro(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
        ]);

        return DB::transaction(function () use ($validated) {
            $clientId = null;
            if ($validated['client_name'] && $validated['client_email']) {
                $client = Person::updateOrCreate(
                ['email' => $validated['client_email']],
                [
                    'name' => $validated['client_name'],
                    'type' => 'client'
                ]
                );
                $clientId = $client->id;
            }

            $trip = Trip::create([
                'user_id' => Auth::id(),
                'title' => $validated['title'],
                'is_pro' => true,
                'status' => 'draft',
                'start_date' => now(),
                'end_date' => now(),
                'travelers' => 1,
                'currency' => 'USD',
                'pro_state' => null
            ]);

            $trip->generateCode();

            if ($clientId) {
                $trip->persons()->attach($clientId);
            }

            return response()->json([
                'success' => true,
                'redirect_url' => route('trips.edit', $trip->id),
                'trip_id' => $trip->id
            ]);
        });
    }


    /**
     * Show the form for editing the specified trip
     */
    public function edit(Trip $trip): View
    {
        // Ensure the trip belongs to the authenticated user or has edit permission
        if (!$trip->canEdit(Auth::id())) {
            abort(403, 'No tienes permiso para editar este viaje.');
        }

        return view('trips.pro-editor', [
            'trip' => $trip->load(['persons'])
        ]);
    }

    /**
     * Get PRO data for a trip (JSON)
     */
    public function getProData(Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        if (!$trip->is_pro) {
            return response()->json(['success' => false, 'message' => 'Este viaje no es PRO'], 400);
        }

        if (!$trip->pro_state) {
            return response()->json(['success' => false, 'message' => 'El viaje no tiene contenido diseñado aún. Por favor, edítalo primero.'], 400);
        }

        return response()->json([
            'success' => true,
            'pro_state' => $trip->pro_state,
            'status' => $trip->status,
            'user_name' => $trip->user->display_name ?? 'Viantryp'
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
            'status' => 'required|in:draft,sent,reserved,completed,discarded'
        ]);

        $trip->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del viaje actualizado exitosamente',
            'trip' => $trip
        ]);
    }

    /**
     * Update trip code
     */
    public function updateCode(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para actualizar este viaje.'
            ], 403);
        }

        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:trips,code,' . $trip->id
        ]);

        $trip->update(['code' => strtoupper($validated['code'])]);

        return response()->json([
            'success' => true,
            'message' => 'Identificador del viaje actualizado exitosamente',
            'trip' => $trip
        ]);
    }

    /**
     * Inline update for trip fields (title, client email)
     */
    public function inlineUpdate(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para actualizar este viaje.'
            ], 403);
        }

        $validated = $request->validate([
            'field' => 'required|in:title,client_email,client_name',
            'value' => 'nullable|string|max:255'
        ]);

        $field = $validated['field'];
        $value = $validated['value'];

        if ($field === 'title') {
            if (empty($value)) {
                return response()->json(['success' => false, 'message' => 'El título es requerido'], 422);
            }
            $trip->update(['title' => $value]);
        }
        elseif ($field === 'client_email') {
            if (empty($value)) {
                // If clearing email, we can just null it out on the existing client if any
                $client = $trip->persons()->where('type', 'client')->first();
                if ($client) {
                    $client->update(['email' => null]);
                }
            } else {
                // Check if a person with this email already exists
                $existingPerson = Person::where('email', $value)->first();
                if ($existingPerson) {
                    // Switch trip to this existing person
                    $trip->persons()->where('type', 'client')->detach();
                    $trip->persons()->attach($existingPerson->id);
                } else {
                    // Update current client or create new one
                    $client = $trip->persons()->where('type', 'client')->first();
                    if ($client) {
                        $client->update(['email' => $value]);
                    } else {
                        $newClient = Person::create([
                            'name' => 'Cliente',
                            'email' => $value,
                            'type' => 'client'
                        ]);
                        $trip->persons()->attach($newClient->id);
                    }
                }
            }
        }
        elseif ($field === 'client_name') {
            $client = $trip->persons()->where('type', 'client')->first();
            if ($client) {
                $client->update(['name' => $value ?: 'Cliente']);
            }
            else if (!empty($value)) {
                $newClient = Person::create([
                    'name' => $value,
                    'email' => null, // Now allowed after migration
                    'type' => 'client'
                ]);
                $trip->persons()->attach($newClient->id);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Actualizado exitosamente'
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

        // Increment views count
        $trip->incrementViews();

        return view('trips.pro-share', [
            'trip' => $trip->load('user', 'documents')
        ]);
    }

    /**
     * Duplicate trip
     */
    public function duplicate(Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user or has view permission
        if (!$trip->canView(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para duplicar este viaje.'
            ], 403);
        }

        $newTrip = $trip->replicate();
        $newTrip->title = $trip->title . ' (Copia)';
        $newTrip->status = Trip::STATUS_DRAFT;
        $newTrip->user_id = Auth::id(); // Ensure the duplicate belongs to the current user
        $newTrip->code = null; // Clear the unique code to avoid constraint violation
        $newTrip->save();

        // Generate a new unique code for the duplicated trip
        $newTrip->generateCode();

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
            $newTrip->code = null; // Clear the unique code to avoid constraint violation
            $newTrip->save();

            // Generate a new unique code for the duplicated trip
            $newTrip->generateCode();

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


        $shareUrl = $trip->getShareUrl();

        return response()->json([
            'success' => true,
            'message' => 'Enlace de compartición generado exitosamente',
            'share_url' => $shareUrl,
            'share_token' => $trip->share_token
        ]);
    }

    /**
     * Save PRO trip state and return share URL
     */
    public function saveProState(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user or has edit permission
        if (!$trip->canEdit(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para guardar cambios en este viaje.'
            ], 403);
        }

        $validated = $request->validate([
            'pro_state' => 'required|array'
        ]);

        $tripData = [
            'pro_state' => $validated['pro_state']
        ];

        // Sync title from pro_state to trips table if present
        if (isset($validated['pro_state']['title'])) {
            $tripData['title'] = $validated['pro_state']['title'];
        }

        // Sync dates from pro_state to trips table
        if (isset($validated['pro_state']['fechaInicio'])) {
            $tripData['start_date'] = $validated['pro_state']['fechaInicio'];
        }
        if (isset($validated['pro_state']['fechaFin'])) {
            $tripData['end_date'] = $validated['pro_state']['fechaFin'];
        }

        $trip->update($tripData);

        $shareUrl = $trip->getShareUrl();

        return response()->json([
            'success' => true,
            'message' => 'Estado PRO guardado exitosamente',
            'share_url' => $shareUrl,
            'share_token' => $trip->share_token
        ]);
    }

    /**
     * Upload attachment for PRO editor elements
     */
    public function uploadAttachment(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para subir documentos a este viaje.'
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png,webp'
        ]);

        $file = $request->file('file');

        // Generate a unique filename
        $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'documents/' . $filename;

        // Store file in public disk
        $stored = \Illuminate\Support\Facades\Storage::disk('public')->put($path, $file->get());

        if (!$stored) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el archivo.'
            ], 500);
        }

        // Create document record
        $document = \App\Models\TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'type' => 'pro_attachment',
            'item_id' => null,
            'original_name' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Archivo subido exitosamente.',
            'url' => route('documents.download', $document->id),
            'original_name' => $file->getClientOriginalName()
        ]);
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
        }
        catch (\Exception $e) {
            Log::error('Error sending trip email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar el correo. Por favor intenta de nuevo.'
            ], 500);
        }
    }

    /**
     * Upload or update trip cover image
     */
    public function uploadCover(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No tienes permiso para actualizar este viaje.'], 403);
        }

        $validated = $request->validate([
            'cover' => 'required|image|max:5120' // max 5MB
        ]);

        $file = $request->file('cover');

        // Fallback: allow a base64 data URL in 'cover_data_url' (set by client FileReader preview)
        $coverDataUrl = $request->input('cover_data_url');

        if (!$file && !$coverDataUrl) {
            return response()->json(['success' => false, 'message' => 'Archivo no encontrado.'], 422);
        }

        // If the client sent a data URL (preview), store that instead of relying on UploadedFile
        if (!$file && $coverDataUrl) {
            try {
                if (preg_match('/^data:(image\/[^;]+);base64,(.*)$/', $coverDataUrl, $matches)) {
                    $mime = $matches[1];
                    $data = base64_decode($matches[2]);
                    $extension = explode('/', $mime)[1] ?? 'png';
                    $filename = 'cover_' . $trip->id . '_' . time() . '.' . $extension;
                    $relativePath = 'trip-covers/' . $filename;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($relativePath, $data);
                    $coverUrl = asset('storage/' . $relativePath);
                }
                else {
                    Log::error('Invalid cover_data_url format', ['trip_id' => $trip->id]);
                    return response()->json(['success' => false, 'message' => 'Formato de imagen inválido.'], 422);
                }
            }
            catch (\Exception $e) {
                Log::error('Cover upload (data URL) exception: ' . $e->getMessage(), ['trip_id' => $trip->id, 'exception' => $e]);
                return response()->json(['success' => false, 'message' => 'Error al guardar la portada desde data URL.', 'exception' => $e->getMessage()], 500);
            }
        }
        else {
            // Guard against missing temporary path (can happen in some PHP configs)
            try {
                $real = $file->getRealPath();
            }
            catch (\Exception $e) {
                $real = null;
            }

            // If real path is empty, try alternative pathname and manual storage
            if (empty($real)) {
                $altPath = $file->getPathname();
                if ($altPath && file_exists($altPath)) {
                    try {
                        $extension = $file->getClientOriginalExtension() ?: pathinfo($altPath, PATHINFO_EXTENSION) ?: 'png';
                        $filename = 'cover_' . $trip->id . '_' . time() . '.' . $extension;
                        $relativePath = 'trip-covers/' . $filename;
                        \Illuminate\Support\Facades\Storage::disk('public')->put($relativePath, fopen($altPath, 'r'));
                        $coverUrl = asset('storage/' . $relativePath);
                    }
                    catch (\Exception $e) {
                        Log::error('Cover upload manual put failed', ['trip_id' => $trip->id, 'exception' => $e->getMessage()]);
                        return response()->json(['success' => false, 'message' => 'No se pudo guardar la imagen desde el archivo temporal.'], 500);
                    }
                }
                else {
                    Log::error('Cover upload failed: uploaded file has no real path and no pathname', ['trip_id' => $trip->id]);
                    return response()->json(['success' => false, 'message' => 'Archivo temporal no disponible en el servidor.'], 500);
                }
            }
            else {
                // Store in public disk under trip-covers
                try {
                    $path = $file->store('trip-covers', 'public');
                    if (!$path) {
                        Log::error('Cover upload store returned false', ['trip_id' => $trip->id]);
                        return response()->json(['success' => false, 'message' => 'No se pudo guardar la imagen en el disco.'], 500);
                    }
                    $coverUrl = asset('storage/' . $path);
                }
                catch (\Exception $e) {
                    Log::error('Cover upload exception: ' . $e->getMessage(), ['trip_id' => $trip->id, 'exception' => $e]);
                    return response()->json(['success' => false, 'message' => 'Error al guardar la portada.', 'exception' => $e->getMessage()], 500);
                }
            }
        }

        // Persist to trip
        $trip->cover_image_url = $coverUrl;
        $trip->save();

        return response()->json(['success' => true, 'cover_url' => $coverUrl]);
    }

    /**
     * Search Unsplash for cover images
     */
    public function searchUnsplash(Request $request)
    {
        $query = $request->input('query', 'travel');
        $page = $request->input('page', 1);
        $perPage = $request->input('per_page', 24);

        $accessKey = config('services.unsplash.access_key');

        if (empty($accessKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Unsplash API key not configured.'
            ], 400);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::get('https://api.unsplash.com/search/photos', [
                'client_id' => $accessKey,
                'query' => $query,
                'page' => $page,
                'per_page' => $perPage,
                'orientation' => 'landscape'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Format response to only send exactly what we need
                $images = [];
                if (isset($data['results'])) {
                    foreach ($data['results'] as $result) {
                        $images[] = [
                            'id' => $result['id'],
                            'url_thumb' => $result['urls']['small'],
                            'url_full' => $result['urls']['regular'],
                            'author_name' => $result['user']['name'] ?? 'Unknown',
                            'author_link' => ($result['user']['links']['html'] ?? '#') . '?utm_source=viantryp&utm_medium=referral',
                        ];
                    }
                }

                return response()->json([
                    'success' => true,
                    'images' => $images,
                    'total_pages' => $data['total_pages'] ?? 1
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error fetching images from Unsplash.',
                'error' => $response->body()
            ], $response->status());

        }
        catch (\Exception $e) {
            Log::error('Unsplash API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Internal server error while fetching images.'
            ], 500);
        }
    }


    /**
     * Invite a collaborator to a trip
     */
    public function inviteCollaborator(Request $request, Trip $trip): JsonResponse
    {
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:editor,viewer'
        ]);

        $token = \Illuminate\Support\Str::random(40);

        \App\Models\TripCollaborator::updateOrCreate(
            ['trip_id' => $trip->id, 'email' => $validated['email']],
            [
                'role' => $validated['role'],
                'token' => $token,
                'accepted_at' => null // Reset if re-inviting
            ]
        );

        $inviteUrl = route('trips.accept-invite', ['token' => $token]);

        try {
            \Illuminate\Support\Facades\Mail::to($validated['email'])->send(new \App\Mail\TripCollaborationInvite($trip, $validated['role'], $inviteUrl));
            return response()->json(['success' => true, 'message' => 'Invitación enviada con éxito.']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending collaboration invite: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al enviar el correo.'], 500);
        }
    }

    /**
     * Accept a collaboration invitation
     */
    public function acceptInvite(string $token)
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login')->with('info', 'Por favor, inicia sesión para aceptar la invitación.');
        }

        $collaborator = \App\Models\TripCollaborator::where('token', $token)->first();

        if (!$collaborator) {
            abort(404, 'Invitación no válida.');
        }

        if ($collaborator->email !== \Illuminate\Support\Facades\Auth::user()->email) {
            abort(403, 'Esta invitación no es para tu correo actual.');
        }

        $collaborator->update([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'accepted_at' => now(),
            'token' => null // Clear token after acceptance
        ]);

        return redirect()->route('trips.index', ['filter' => 'shared'])
            ->with('success', "Ahora colaboras en el viaje: {$collaborator->trip->title}");
    }

    /**
     * Transfer ownership of a trip to another user
     */
    public function transferOwnership(Request $request, Trip $trip): JsonResponse
    {
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $newOwner = \App\Models\User::where('email', $validated['email'])->first();

        if (!$newOwner) {
            return response()->json(['success' => false, 'message' => 'El usuario no existe en el sistema.'], 404);
        }

        if ($newOwner->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Ya eres el propietario de este viaje.'], 400);
        }

        $oldOwnerId = $trip->user_id;

        // Perform transfer
        $trip->user_id = $newOwner->id;
        $trip->save();

        // Ensure old owner remains as editor
        TripCollaborator::updateOrCreate(
            ['trip_id' => $trip->id, 'user_id' => $oldOwnerId],
            [
                'email' => Auth::user()->email,
                'role' => 'editor',
                'accepted_at' => now(),
                'token' => null
            ]
        );

        // Remove new owner from collaborators if they were there
        TripCollaborator::where('trip_id', $trip->id)
            ->where('user_id', $newOwner->id)
            ->delete();

        return response()->json(['success' => true, 'message' => "Viaje transferido a {$newOwner->name} exitosamente."]);
    }
}
