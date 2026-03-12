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
     * Download or preview a document
     */
    public function download(Request $request, TripDocument $document)
    {
        $token = $request->query('token');

        // Access control: either authenticated owner or public access via valid trip token
        $isAuthorized = false;

        if (Auth::check() && $document->user_id === Auth::id()) {
            $isAuthorized = true;
        } elseif ($token) {
            $trip = Trip::findByShareToken($token);
            if ($trip && $trip->id === $document->trip_id) {
                $isAuthorized = true;
            }
        }

        if (!$isAuthorized) {
            abort(403, 'No tienes permiso para acceder a este documento.');
        }

        $filePath = storage_path('app/public/' . $document->path);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado.');
        }

        // Return file for inline preview instead of forced download
        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $document->original_name . '"'
        ]);
    }
}
