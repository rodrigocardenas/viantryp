<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCopilotRequest;
use App\Models\Trip;
use App\Models\TripDocument;
use App\Services\TripAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TripAiController extends Controller
{
    protected TripAiService $aiService;

    public function __construct(TripAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle AI chat agent requests with optional files.
     */
    public function handleChat(StoreCopilotRequest $request, Trip $trip): JsonResponse
    {
        // 1. Authorization check
        if (!$trip->canEdit(Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar este itinerario.'
            ], 403);
        }

        $message = $request->input('message');
        $uploadedFilesInfo = [];
        $createdDocuments = [];

        // 2. Process and save uploaded files within a database transaction
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            if (!is_array($files)) {
                $files = [$files];
            }

            DB::transaction(function () use ($files, $trip, &$uploadedFilesInfo, &$createdDocuments) {
                foreach ($files as $file) {
                    if (!$file || !$file->isValid()) {
                        continue;
                    }

                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension() ?: 'bin';
                    $filename = Str::random(40) . '.' . $extension;
                    $path = $file->storeAs('trip-documents', $filename, 'public');

                    if ($path) {
                        $fullDiskPath = Storage::disk('public')->path($path);

                        // Create TripDocument record
                        $doc = TripDocument::create([
                            'trip_id' => $trip->id,
                            'user_id' => Auth::id(),
                            'type' => 'pro_attachment',
                            'item_id' => null,
                            'original_name' => $originalName,
                            'filename' => $filename,
                            'path' => $path,
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize()
                        ]);

                        $docData = [
                            'id' => $doc->id,
                            'original_name' => $originalName,
                            'filename' => $filename,
                            'path' => $fullDiskPath,
                            'url' => asset('storage/' . $path),
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize()
                        ];

                        $uploadedFilesInfo[] = $docData;
                        $createdDocuments[] = $docData;
                    }
                }
            });
        }

        // Check if there is anything to process
        if (empty($message) && empty($uploadedFilesInfo)) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor escribe un mensaje o adjunta al menos un archivo.'
            ], 422);
        }

        // 4. Send to TripAiService
        $aiResult = $this->aiService->processConversation($trip, $message, $uploadedFilesInfo);

        // 5. Link document attachments to action data
        if (!empty($aiResult['actions']) && is_array($aiResult['actions'])) {
            foreach ($aiResult['actions'] as &$action) {
                $fileIndex = $action['attach_file_index'] ?? null;
                if ($fileIndex !== null && isset($createdDocuments[$fileIndex])) {
                    $matchedDoc = $createdDocuments[$fileIndex];
                    $action['data']['attachment_url'] = $matchedDoc['url'];
                    $action['data']['attachment_name'] = $matchedDoc['original_name'];
                    $action['data']['document_id'] = $matchedDoc['id'];
                } elseif (count($createdDocuments) === 1) {
                    // If exactly one file was uploaded, attach it by default
                    $matchedDoc = $createdDocuments[0];
                    $action['data']['attachment_url'] = $matchedDoc['url'];
                    $action['data']['attachment_name'] = $matchedDoc['original_name'];
                    $action['data']['document_id'] = $matchedDoc['id'];
                }
            }
        }

        return response()->json([
            'success' => $aiResult['success'] ?? true,
            'message' => $aiResult['message'] ?? 'He procesado tu información.',
            'actions' => $aiResult['actions'] ?? [],
            'suggestions' => $aiResult['suggestions'] ?? [],
            'documents' => $createdDocuments
        ]);
    }
}
