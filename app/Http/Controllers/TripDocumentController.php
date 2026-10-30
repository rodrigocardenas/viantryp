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
        // Ensure the user has permission to delete: either they uploaded it, own the trip, or are an editor of the trip
        $canDelete = Auth::id() === $document->user_id || 
                     ($document->trip && (Auth::id() === $document->trip->user_id || $document->trip->canEdit(Auth::id())));

        if (!$canDelete) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para eliminar este documento.'
            ], 403);
        }

        // Delete file from storage (check both local private disk and legacy public disk)
        if (Storage::disk('local')->exists($document->path)) {
            Storage::disk('local')->delete($document->path);
        }
        if (Storage::disk('public')->exists($document->path)) {
            Storage::disk('public')->delete($document->path);
        }

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

        // Access control: either authenticated owner/collaborator or public access via valid trip token
        $isAuthorized = false;

        if (Auth::check()) {
            if ($document->user_id === Auth::id() || ($document->trip && $document->trip->canView(Auth::id()))) {
                $isAuthorized = true;
            }
        } elseif ($token) {
            $trip = Trip::findByShareToken($token);
            if ($trip && $trip->id === $document->trip_id) {
                $isAuthorized = true;
            }
        }

        if (!$isAuthorized) {
            abort(403, 'No tienes permiso para acceder a este documento.');
        }

        // Dual-disk lookup: check private local disk first, then fallback to public disk for legacy attachments
        $disk = null;
        if (Storage::disk('local')->exists($document->path)) {
            $disk = 'local';
        } elseif (Storage::disk('public')->exists($document->path)) {
            $disk = 'public';
        }

        if (!$disk) {
            abort(404, 'Archivo no encontrado.');
        }

        $filePath = Storage::disk($disk)->path($document->path);
        $safeFilename = str_replace(['"', "\r", "\n", '\\'], '', $document->original_name ?: 'documento');

        // Return file for inline preview instead of forced download
        return response()->file($filePath, [
            'Content-Disposition' => 'inline; filename="' . $safeFilename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
