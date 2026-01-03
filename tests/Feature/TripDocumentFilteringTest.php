<?php

namespace Tests\Feature;

use App\Models\Trip;
use App\Models\User;
use App\Models\TripDocument;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TripDocumentFilteringTest extends TestCase
{
    /**
     * Test that documents are filtered by item ID in preview
     */
    public function test_documents_are_filtered_by_item_id(): void
    {
        $user = User::factory()->create();

        // Create a trip with multiple items
        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Document Filtering Test',
            'code' => 'DOC' . rand(1000, 9999),
            'start_date' => now(),
            'end_date' => now()->addDays(1),
            'travelers' => 1,
            'destination' => 'Test',
            'status' => 'draft',
            'items_data' => [
                [
                    'day' => 1,
                    'type' => 'flight',
                    'title' => 'Flight 1',
                    'airline_name' => 'Test Air'
                ],
                [
                    'day' => 1,
                    'type' => 'flight',
                    'title' => 'Flight 2',
                    'airline_name' => 'Test Air'
                ],
                [
                    'day' => 1,
                    'type' => 'hotel',
                    'title' => 'Hotel 1',
                    'check_in' => '2024-01-15'
                ]
            ]
        ]);

        // Refresh to get the IDs
        $trip->refresh();

        // Get the item IDs
        $flight1Id = $trip->items_data[0]['id'];
        $flight2Id = $trip->items_data[1]['id'];
        $hotelId = $trip->items_data[2]['id'];

        // Create documents for specific items
        TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'type' => 'flight',
            'item_id' => $flight1Id,
            'original_name' => 'boarding_pass_flight1.pdf',
            'filename' => 'boarding_pass_flight1.pdf',
            'path' => 'docs/boarding_pass_flight1.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100
        ]);

        TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'type' => 'flight',
            'item_id' => $flight2Id,
            'original_name' => 'boarding_pass_flight2.pdf',
            'filename' => 'boarding_pass_flight2.pdf',
            'path' => 'docs/boarding_pass_flight2.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100
        ]);

        TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'type' => 'hotel',
            'item_id' => $hotelId,
            'original_name' => 'hotel_confirmation.pdf',
            'filename' => 'hotel_confirmation.pdf',
            'path' => 'docs/hotel_confirmation.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100
        ]);

        // Test filtering by item ID
        $flight1Docs = $trip->getDocumentsByItemId($flight1Id);
        $this->assertCount(1, $flight1Docs);
        $this->assertEquals('boarding_pass_flight1.pdf', $flight1Docs->first()->original_name);

        $flight2Docs = $trip->getDocumentsByItemId($flight2Id);
        $this->assertCount(1, $flight2Docs);
        $this->assertEquals('boarding_pass_flight2.pdf', $flight2Docs->first()->original_name);

        $hotelDocs = $trip->getDocumentsByItemId($hotelId);
        $this->assertCount(1, $hotelDocs);
        $this->assertEquals('hotel_confirmation.pdf', $hotelDocs->first()->original_name);

        // Test that getDocumentsByItemId returns empty for non-existent item
        $nonExistentDocs = $trip->getDocumentsByItemId('nonexistent_id');
        $this->assertCount(0, $nonExistentDocs);
    }

    /**
     * Test that preview page shows correct documents for each item
     */
    public function test_preview_page_shows_item_specific_documents(): void
    {
        $user = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Preview Document Test',
            'code' => 'PRV' . rand(1000, 9999),
            'start_date' => now(),
            'end_date' => now()->addDays(1),
            'travelers' => 1,
            'destination' => 'Test',
            'status' => 'sent',
            'items_data' => [
                [
                    'day' => 1,
                    'type' => 'flight',
                    'title' => 'Flight 1',
                    'airline_name' => 'Test Air',
                    'flight_number' => 'TA123',
                    'departure_airport' => 'BOG',
                    'arrival_airport' => 'MIA'
                ]
            ]
        ]);

        $trip->refresh();
        $flightId = $trip->items_data[0]['id'];

        // Create a document for this specific flight
        TripDocument::create([
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'type' => 'flight',
            'item_id' => $flightId,
            'original_name' => 'test_boarding.pdf',
            'filename' => 'test_boarding.pdf',
            'path' => 'docs/test_boarding.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100
        ]);

        // Test the preview page
        $response = $this->get(route('trips.preview', $trip));

        $response->assertStatus(200);
        $response->assertSee('test_boarding.pdf');
        $response->assertSee('Test Air');
        $response->assertSee('TA123');
    }

    /**
     * Test that temporary document item IDs are updated to real IDs when trip is saved
     * Documents uploaded during item creation are linked to temp IDs, then converted to real IDs
     */
    public function test_temporary_document_ids_are_cleared_when_trip_is_saved(): void
    {
        $userId = User::factory()->create()->id;

        // Create a trip with NO items initially
        $tripId = DB::table('trips')->insertGetId([
            'user_id' => $userId,
            'title' => 'Multi-Item Test',
            'code' => 'MULTI' . rand(1000, 9999),
            'start_date' => now(),
            'end_date' => now()->addDays(2),
            'travelers' => 1,
            'destination' => 'Test',
            'status' => 'draft',
            'items_data' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create a document with a temp ID
        $tempId = 'temp_' . time() . '_abc123';
        $docId = DB::table('trip_documents')->insertGetId([
            'trip_id' => $tripId,
            'user_id' => $userId,
            'type' => 'flight',
            'item_id' => $tempId,
            'original_name' => 'doc1.pdf',
            'filename' => 'doc1.pdf',
            'path' => 'docs/doc1.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Verify the temp ID was saved
        $docBeforeUpdate = DB::table('trip_documents')->where('id', $docId)->first();
        $this->assertEquals($tempId, $docBeforeUpdate->item_id);

        // Now update the trip with items using the model
        $trip = Trip::find($tripId);
        $trip->update([
            'items_data' => [
                [
                    'day' => 1,
                    'type' => 'flight',
                    'title' => 'Flight 1',
                    'airline_name' => 'Airline 1',
                    'temp_id' => $tempId
                ],
                [
                    'day' => 2,
                    'type' => 'flight',
                    'title' => 'Flight 2',
                    'airline_name' => 'Airline 2',
                    'temp_id' => 'temp_other_id'
                ]
            ]
        ]);

        // Verify the document was updated (check DB directly to avoid transaction issues)
        $docAfterUpdate = DB::table('trip_documents')->where('id', $docId)->first();
        $this->assertNotNull($docAfterUpdate);
        // Should be converted from temp_<timestamp>_abc123 to day_1_flight_1 (first flight item)
        $this->assertEquals('day_1_flight_1', $docAfterUpdate->item_id);
    }

    public function test_documents_for_multiple_items_same_type_are_correctly_matched(): void
    {
        $userId = User::factory()->create()->id;

        // Create a trip with NO items initially
        $tripId = DB::table('trips')->insertGetId([
            'user_id' => $userId,
            'title' => 'Multi-Flight Test',
            'code' => 'FLTS' . rand(1000, 9999),
            'start_date' => now(),
            'end_date' => now()->addDays(2),
            'travelers' => 1,
            'destination' => 'Test',
            'status' => 'draft',
            'items_data' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Create documents with different temp IDs for different flights
        $tempId1 = 'temp_' . time() . '_flight1';
        $tempId2 = 'temp_' . time() . '_flight2';

        $doc1Id = DB::table('trip_documents')->insertGetId([
            'trip_id' => $tripId,
            'user_id' => $userId,
            'type' => 'flight',
            'item_id' => $tempId1,
            'original_name' => 'ticket1.pdf',
            'filename' => 'ticket1.pdf',
            'path' => 'docs/ticket1.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $doc2Id = DB::table('trip_documents')->insertGetId([
            'trip_id' => $tripId,
            'user_id' => $userId,
            'type' => 'flight',
            'item_id' => $tempId2,
            'original_name' => 'ticket2.pdf',
            'filename' => 'ticket2.pdf',
            'path' => 'docs/ticket2.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Now update the trip with two flights, each with their own temp_id
        $trip = Trip::find($tripId);
        $trip->update([
            'items_data' => [
                [
                    'day' => 1,
                    'type' => 'flight',
                    'title' => 'Flight 1 - Day 1',
                    'airline_name' => 'Airline A',
                    'temp_id' => $tempId1
                ],
                [
                    'day' => 2,
                    'type' => 'flight',
                    'title' => 'Flight 2 - Day 2',
                    'airline_name' => 'Airline B',
                    'temp_id' => $tempId2
                ]
            ]
        ]);

        // Verify each document was updated to the correct item ID
        $doc1AfterUpdate = DB::table('trip_documents')->where('id', $doc1Id)->first();
        $this->assertEquals('day_1_flight_1', $doc1AfterUpdate->item_id);

        $doc2AfterUpdate = DB::table('trip_documents')->where('id', $doc2Id)->first();
        $this->assertEquals('day_2_flight_1', $doc2AfterUpdate->item_id);
    }
}
