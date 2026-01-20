<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Trip;

class TripItemIdGenerationTest extends TestCase
{
    /**
     * Test that item IDs are generated with correct format
     */
    public function test_item_ids_are_generated_with_correct_format(): void
    {
        $trip = new Trip();

        $items = [
            ['day' => 1, 'type' => 'flight', 'title' => 'Flight 1'],
            ['day' => 1, 'type' => 'flight', 'title' => 'Flight 2'],
            ['day' => 1, 'type' => 'hotel', 'title' => 'Hotel 1'],
            ['day' => 2, 'type' => 'flight', 'title' => 'Flight 3'],
        ];

        $processed = $trip->addItemIds($items);

        // Check first flight
        $this->assertEquals('day_1_flight_1', $processed[0]['id']);
        $this->assertEquals('Flight 1', $processed[0]['title']);

        // Check second flight
        $this->assertEquals('day_1_flight_2', $processed[1]['id']);
        $this->assertEquals('Flight 2', $processed[1]['title']);

        // Check hotel
        $this->assertEquals('day_1_hotel_1', $processed[2]['id']);
        $this->assertEquals('Hotel 1', $processed[2]['title']);

        // Check flight on day 2
        $this->assertEquals('day_2_flight_1', $processed[3]['id']);
        $this->assertEquals('Flight 3', $processed[3]['title']);
    }

    /**
     * Test that global items (without day) get correct IDs
     */
    public function test_global_items_get_correct_ids(): void
    {
        $trip = new Trip();

        $items = [
            ['type' => 'note', 'title' => 'Global Note 1'],
            ['type' => 'note', 'title' => 'Global Note 2'],
            ['day' => 1, 'type' => 'note', 'title' => 'Day 1 Note'],
        ];

        $processed = $trip->addItemIds($items);

        // Check global notes
        $this->assertEquals('day_global_note_1', $processed[0]['id']);
        $this->assertEquals('day_global_note_2', $processed[1]['id']);

        // Check day 1 note
        $this->assertEquals('day_1_note_1', $processed[2]['id']);
    }

    /**
     * Test that existing IDs are not overwritten
     */
    public function test_existing_ids_are_not_overwritten(): void
    {
        $trip = new Trip();

        $items = [
            ['id' => 'custom_id_1', 'day' => 1, 'type' => 'flight', 'title' => 'Flight 1'],
            ['day' => 1, 'type' => 'flight', 'title' => 'Flight 2'],
        ];

        $processed = $trip->addItemIds($items);

        // Existing ID should remain
        $this->assertEquals('custom_id_1', $processed[0]['id']);

        // New ID should be generated for the second item
        $this->assertEquals('day_1_flight_1', $processed[1]['id']);
    }

    /**
     * Test find item by ID method
     */
    public function test_find_item_by_id(): void
    {
        $trip = new Trip([
            'items_data' => [
                ['id' => 'day_1_flight_1', 'day' => 1, 'type' => 'flight', 'title' => 'Flight 1'],
                ['id' => 'day_1_hotel_1', 'day' => 1, 'type' => 'hotel', 'title' => 'Hotel 1'],
            ]
        ]);

        $foundFlight = $trip->findItemById('day_1_flight_1');
        $this->assertNotNull($foundFlight);
        $this->assertEquals('Flight 1', $foundFlight['title']);

        $foundHotel = $trip->findItemById('day_1_hotel_1');
        $this->assertNotNull($foundHotel);
        $this->assertEquals('Hotel 1', $foundHotel['title']);

        $notFound = $trip->findItemById('nonexistent_id');
        $this->assertNull($notFound);
    }

    /**
     * Test get items by day method
     */
    public function test_get_items_by_day(): void
    {
        $trip = new Trip([
            'items_data' => [
                ['id' => 'day_1_flight_1', 'day' => 1, 'type' => 'flight'],
                ['id' => 'day_1_hotel_1', 'day' => 1, 'type' => 'hotel'],
                ['id' => 'day_2_flight_1', 'day' => 2, 'type' => 'flight'],
            ]
        ]);

        $day1Items = $trip->getItemsByDay(1);
        $this->assertCount(2, $day1Items);

        $day2Items = $trip->getItemsByDay(2);
        $this->assertCount(1, $day2Items);
    }
}
