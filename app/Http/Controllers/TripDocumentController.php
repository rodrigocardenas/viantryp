<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripDocument;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TripDocumentController extends Controller
{
    /**
     * Upload a document for a trip item
     */
    public function upload(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para subir documentos a este viaje.'
            ], 403);
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png',
            'type' => 'required|in:flight,hotel,transport,activity',
            'item_id' => 'nullable|string'
        ]);

        $file = $request->file('file');

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'documents/' . $filename;

        // Store file
        $stored = Storage::disk('public')->put($path, $file->get());

        if (!$stored) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el archivo.'
            ], 500);
        }

        // Create document record
        $document = TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'item_id' => $request->item_id,
            'original_name' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Documento subido exitosamente.',
            'document' => $document
        ]);
    }

    /**
     * Upload a document temporarily (for trips not yet saved)
     */
    public function tempUpload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png',
            'type' => 'required|in:flight,hotel,transport,activity',
            'item_id' => 'nullable|string'
        ]);

        $file = $request->file('file');

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'temp/' . $filename;

        // Store file temporarily
        $stored = Storage::disk('public')->put($path, $file->get());

        if (!$stored) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el archivo.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Documento subido temporalmente.',
            'file_info' => [
                'filename' => $filename,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'type' => $request->type,
                'item_id' => $request->item_id,
                'is_temporary' => true
            ]
        ]);
    }

    /**
     * Process temporary file and move to permanent storage
     */
    public function processTemp(Request $request): JsonResponse
    {
        $request->validate([
            'temp_path' => 'required|string',
            'trip_id' => 'required|integer|exists:trips,id',
            'type' => 'required|in:flight,hotel,transport,activity',
            'item_id' => 'nullable|string'
        ]);

        // Get the trip to ensure user owns it
        $trip = Trip::findOrFail($request->trip_id);
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para procesar documentos de este viaje.'
            ], 403);
        }

        $tempPath = 'public/' . $request->temp_path;
        $fullTempPath = storage_path('app/' . $tempPath);

        // Check if temporary file exists
        if (!file_exists($fullTempPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Archivo temporal no encontrado.'
            ], 404);
        }

        // Generate new path for permanent storage
        $filename = pathinfo($request->temp_path, PATHINFO_BASENAME);
        $newPath = 'documents/' . $filename;

        // Move file from temp to permanent storage
        $moved = Storage::disk('public')->put($newPath, file_get_contents($fullTempPath));

        if (!$moved) {
            return response()->json([
                'success' => false,
                'message' => 'Error al mover el archivo a almacenamiento permanente.'
            ], 500);
        }

        // Delete the temporary file
        Storage::disk('public')->delete($request->temp_path);

        // Create document record
        $document = TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'item_id' => $request->item_id,
            'original_name' => pathinfo($filename, PATHINFO_FILENAME),
            'filename' => $filename,
            'path' => $newPath,
            'mime_type' => 'application/octet-stream', // Default mime type
            'size' => filesize(storage_path('app/public/' . $newPath))
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Archivo procesado exitosamente.',
            'document' => $document
        ]);
    }

    /**
     * Get documents for a trip item
     */
    public function getByItem(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver documentos de este viaje.'
            ], 403);
        }

        $request->validate([
            'type' => 'required|in:flight,hotel,transport',
            'item_id' => 'nullable|string'
        ]);

        $documents = TripDocument::where('trip_id', $trip->id)
            ->where('type', $request->type)
            ->where('item_id', $request->item_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'documents' => $documents
        ]);
    }

    /**
     * Update the item_id of a document
     */
    public function updateItemId(Request $request, Trip $trip): JsonResponse
    {
        // Ensure the trip belongs to the authenticated user
        if ($trip->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para actualizar documentos de este viaje.'
            ], 403);
        }

        $request->validate([
            'documents' => 'required|array',
            'documents.*.id' => 'required|integer',
            'documents.*.item_id' => 'required|string'
        ]);

        try {
            foreach ($request->documents as $docData) {
                $document = TripDocument::where('id', $docData['id'])
                    ->where('trip_id', $trip->id)
                    ->first();

                if ($document && $document->user_id === Auth::id()) {
                    $document->update(['item_id' => $docData['item_id']]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Documentos actualizados exitosamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar documentos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a document
     */
    public function destroy(TripDocument $document): JsonResponse
    {
        // Ensure the document belongs to the authenticated user
        if ($document->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este documento.'
            ], 403);
        }

        // Delete file from storage
        Storage::disk('public')->delete($document->path);

        // Delete record
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Documento eliminado exitosamente.'
        ]);
    }

    /**
     * Download a document
     */
    public function download(TripDocument $document)
    {
        // Ensure the document belongs to the authenticated user
        if ($document->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para descargar este documento.');
        }

        $filePath = storage_path('app/public/' . $document->path);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->download($filePath, $document->original_name);
    }
}
