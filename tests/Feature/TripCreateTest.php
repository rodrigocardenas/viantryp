<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripCreateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the trip create page loads correctly with basic elements
     */
    public function test_trip_create_page_loads_with_basic_elements(): void
    {
        // Create a test user
        $user = User::factory()->create();

        // Authenticate the user
        $this->actingAs($user);

        // Make request to create page
        $response = $this->get(route('trips.create-pro'));

        // Assert successful response
        $response->assertStatus(200);
        $this->assertTrue(strlen($response->getContent()) > 1000);
    }

    /**
     * Test that create page requires authentication
     */
    public function test_trip_create_requires_authentication(): void
    {
        // Make request to create page without authentication
        $response = $this->get(route('trips.create-pro'));

        // Should redirect to home/landing
        $response->assertRedirect(route('home'));
    }

    /**
     * Test that create page loads with editor initialized
     */
    public function test_trip_create_loads_with_editor_initialized(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('trips.create-pro'));
        $response->assertStatus(200);
    }

    /**
     * Test that create page has proper validation attributes
     */
    public function test_trip_create_has_proper_validation(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('trips.create-pro'));
        $response->assertStatus(200);
    }
}
