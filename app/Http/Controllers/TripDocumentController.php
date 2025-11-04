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
            'type' => 'required|in:flight,hotel,transport',
            'item_id' => 'nullable|string'
        ]);

        $file = $request->file('file');

        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'documents/' . $filename;

        // Store file
        $stored = Storage::disk('public')->put($path, file_get_contents($file));

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
