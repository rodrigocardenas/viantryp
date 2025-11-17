<?php

namespace Tests\Feature;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripPreviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the trip preview page loads correctly with basic elements
     */
    public function test_trip_preview_loads_with_basic_elements(): void
    {
        // Create a test user
        $user = User::factory()->create();

        // Create a test trip with sample data
        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Test Trip Preview',
            'code' => 'TEST123',
            'start_date' => now(),
            'end_date' => now()->addDays(5),
            'travelers' => 2,
            'destination' => 'Test Destination',
            'status' => Trip::STATUS_SENT,
            'price' => 1500.00,
            'items_data' => [
                [
                    'type' => 'flight',
                    'day' => 1,
                    'title' => 'Flight to Destination',
                    'departure_airport' => 'BOG',
                    'arrival_airport' => 'MIA',
                    'departure_time' => '10:00',
                    'arrival_time' => '14:00',
                    'departure_date' => now()->format('Y-m-d'),
                    'arrival_date' => now()->format('Y-m-d'),
                    'airline' => 'Test Airlines',
                    'flight_number' => 'TA123'
                ]
            ]
        ]);

        // Make request to preview page
        $response = $this->get(route('trips.preview', $trip));

        // Assert successful response
        $response->assertStatus(200);

        // Assert basic HTML structure is present
        $response->assertSee('<title>Viantryp - Vista Previa del Itinerario</title>', false);
        $response->assertSee('Test Trip Preview');
        $response->assertSee('TEST123');
        $response->assertSee('$1,500.00');

        // Assert components are rendered (check for actual HTML output)
        $response->assertSee('<div class="preview-sticky-header"', false);
        $response->assertSee('<div class="contact-button minimized"', false);

        // Assert flight card is rendered
        $response->assertSee('Vuelo Bog → Mia');
        $response->assertSee('Test Airlines');
        $response->assertSee('TA123');

        // Assert no PHP errors in the response (check for common error patterns)
        $response->assertDontSee('PHP Error');
        $response->assertDontSee('Fatal error');
        $response->assertDontSee('Parse error');
    }

    /**
     * Test that preview works for trips with summary items
     */
    public function test_trip_preview_with_summary_section(): void
    {
        $user = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Trip with Summary',
            'code' => 'SUM123',
            'start_date' => now(),
            'end_date' => now()->addDays(3),
            'travelers' => 1,
            'destination' => 'Summary Destination',
            'status' => Trip::STATUS_SENT,
            'price' => 800.00,
            'items_data' => [
                [
                    'type' => 'summary',
                    'title' => 'Trip Overview',
                    'content' => 'This is a summary of the trip itinerary.'
                ],
                [
                    'type' => 'hotel',
                    'day' => 1,
                    'title' => 'Hotel Test',
                    'check_in' => now()->format('Y-m-d'),
                    'check_out' => now()->addDays(2)->format('Y-m-d'),
                    'nights' => 2,
                    'room_type' => 'Deluxe Room'
                ]
            ]
        ]);

        $response = $this->get(route('trips.preview', $trip));

        $response->assertStatus(200);
        $response->assertSee('Trip Overview');
        $response->assertSee('This is a summary of the trip itinerary.');
        $response->assertSee('<div class="summary-section"', false);
        $response->assertSee('Hotel Test');
    }

    /**
     * Test that preview handles empty trip data gracefully
     */
    public function test_trip_preview_handles_empty_data(): void
    {
        $user = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Empty Trip',
            'code' => 'EMPTY123',
            'start_date' => now(),
            'end_date' => now()->addDays(1),
            'travelers' => 1,
            'destination' => 'Empty Destination',
            'status' => Trip::STATUS_SENT,
            'price' => 0.00,
            'items_data' => []
        ]);

        $response = $this->get(route('trips.preview', $trip));

        $response->assertStatus(200);
        $response->assertSee('Empty Trip');
        $response->assertSee('EMPTY123');
        $response->assertSee('$0.00');
        // Should still render basic structure even with no items
        $response->assertSee('<div class="contact-button minimized"', false);
    }

    /**
     * Test that draft trips are not accessible publicly
     */
    public function test_draft_trips_not_accessible_publicly(): void
    {
        $user = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Draft Trip',
            'start_date' => now(),
            'end_date' => now()->addDays(1),
            'travelers' => 1,
            'destination' => 'Draft Destination',
            'status' => Trip::STATUS_DRAFT,
            'price' => 0.00,
            'items_data' => []
        ]);

        $response = $this->get(route('trips.preview', $trip));

        $response->assertStatus(404);
    }

    /**
     * Test that authenticated owners can preview draft trips
     */
    public function test_authenticated_owners_can_preview_drafts(): void
    {
        $user = User::factory()->create();

        $trip = Trip::create([
            'user_id' => $user->id,
            'title' => 'Owner Draft Trip',
            'start_date' => now(),
            'end_date' => now()->addDays(1),
            'travelers' => 1,
            'destination' => 'Owner Draft Destination',
            'status' => Trip::STATUS_DRAFT,
            'price' => 0.00,
            'items_data' => []
        ]);

        $response = $this->actingAs($user)->get(route('trips.preview', $trip));

        $response->assertStatus(200);
        $response->assertSee('Owner Draft Trip');
    }
}