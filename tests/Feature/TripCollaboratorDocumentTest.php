<?php

namespace Tests\Feature;

use App\Models\Trip;
use App\Models\User;
use App\Models\TripDocument;
use App\Models\TripCollaborator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TripCollaboratorDocumentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an editor collaborator can upload attachments to a shared trip.
     */
    public function test_editor_collaborator_can_upload_attachments(): void
    {
        Storage::fake('public');

        $owner = User::factory()->create();
        $editor = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $owner->id,
            'title' => 'Viaje Compartido',
            'code' => 'COLAB123',
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'travelers' => 2,
            'destination' => 'Test Destination',
            'status' => Trip::STATUS_DRAFT,
            'price' => 1000.00
        ]);

        // Add editor collaborator
        TripCollaborator::create([
            'trip_id' => $trip->id,
            'user_id' => $editor->id,
            'email' => $editor->email,
            'role' => 'editor',
            'accepted_at' => now()
        ]);

        $this->actingAs($editor);

        $file = UploadedFile::fake()->create('voucher.pdf', 500, 'application/pdf');

        $response = $this->postJson(route('trips.upload-attachment', $trip), [
            'file' => $file
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'document_id',
            'url',
            'original_name'
        ]);

        $this->assertDatabaseHas('trip_documents', [
            'trip_id' => $trip->id,
            'user_id' => $editor->id,
            'original_name' => 'voucher.pdf'
        ]);
    }

    /**
     * Test that owner and authorized collaborators can download documents, but unrelated users are blocked.
     */
    public function test_owner_and_collaborators_can_download_document(): void
    {
        Storage::fake('public');

        $owner = User::factory()->create();
        $editor = User::factory()->create();
        $viewer = User::factory()->create();
        $unrelated = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $owner->id,
            'title' => 'Viaje Compartido',
            'code' => 'COLAB123',
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'travelers' => 2,
            'destination' => 'Test Destination',
            'status' => Trip::STATUS_DRAFT,
            'price' => 1000.00
        ]);

        // Add collaborators
        TripCollaborator::create([
            'trip_id' => $trip->id,
            'user_id' => $editor->id,
            'email' => $editor->email,
            'role' => 'editor',
            'accepted_at' => now()
        ]);

        TripCollaborator::create([
            'trip_id' => $trip->id,
            'user_id' => $viewer->id,
            'email' => $viewer->email,
            'role' => 'viewer',
            'accepted_at' => now()
        ]);

        // Create a fake file in storage
        $path = 'documents/test_file.pdf';
        Storage::disk('public')->put($path, 'dummy content');

        // Document uploaded by the editor collaborator
        $document = TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => $editor->id,
            'type' => 'pro_attachment',
            'original_name' => 'ticket.pdf',
            'filename' => 'test_file.pdf',
            'path' => $path,
            'mime_type' => 'application/pdf',
            'size' => 100
        ]);

        // 1. Editor collaborator can download
        $this->actingAs($editor);
        $response = $this->get(route('documents.download', $document));
        $response->assertStatus(200);

        // 2. Owner can download
        $this->actingAs($owner);
        $response = $this->get(route('documents.download', $document));
        $response->assertStatus(200);

        // 3. Viewer collaborator can download
        $this->actingAs($viewer);
        $response = $this->get(route('documents.download', $document));
        $response->assertStatus(200);

        // 4. Unrelated user is blocked
        $this->actingAs($unrelated);
        $response = $this->get(route('documents.download', $document));
        $response->assertStatus(403);
    }

    /**
     * Test that owner and editor collaborators can delete documents, but viewers and unrelated users are blocked.
     */
    public function test_owner_and_editor_collaborators_can_delete_document(): void
    {
        Storage::fake('public');

        $owner = User::factory()->create();
        $editor = User::factory()->create();
        $viewer = User::factory()->create();
        $unrelated = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $owner->id,
            'title' => 'Viaje Compartido',
            'code' => 'COLAB123',
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'travelers' => 2,
            'destination' => 'Test Destination',
            'status' => Trip::STATUS_DRAFT,
            'price' => 1000.00
        ]);

        // Add collaborators
        TripCollaborator::create([
            'trip_id' => $trip->id,
            'user_id' => $editor->id,
            'email' => $editor->email,
            'role' => 'editor',
            'accepted_at' => now()
        ]);

        TripCollaborator::create([
            'trip_id' => $trip->id,
            'user_id' => $viewer->id,
            'email' => $viewer->email,
            'role' => 'viewer',
            'accepted_at' => now()
        ]);

        // 1. Unrelated user trying to delete gets 403
        $doc1 = TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => $editor->id,
            'type' => 'pro_attachment',
            'original_name' => 'ticket1.pdf',
            'filename' => 'ticket1.pdf',
            'path' => 'documents/ticket1.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100
        ]);
        Storage::disk('public')->put('documents/ticket1.pdf', 'dummy');

        $this->actingAs($unrelated);
        $response = $this->deleteJson(route('documents.destroy', $doc1));
        $response->assertStatus(403);

        // 2. Viewer collaborator trying to delete gets 403
        $this->actingAs($viewer);
        $response = $this->deleteJson(route('documents.destroy', $doc1));
        $response->assertStatus(403);

        // 3. Editor collaborator can delete their own uploaded document
        $this->actingAs($editor);
        $response = $this->deleteJson(route('documents.destroy', $doc1));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('trip_documents', ['id' => $doc1->id]);

        // 4. Owner can delete document uploaded by editor collaborator
        $doc2 = TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => $editor->id,
            'type' => 'pro_attachment',
            'original_name' => 'ticket2.pdf',
            'filename' => 'ticket2.pdf',
            'path' => 'documents/ticket2.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100
        ]);
        Storage::disk('public')->put('documents/ticket2.pdf', 'dummy');

        $this->actingAs($owner);
        $response = $this->deleteJson(route('documents.destroy', $doc2));
        $response->assertStatus(200);
        $this->assertDatabaseMissing('trip_documents', ['id' => $doc2->id]);
    }
}
