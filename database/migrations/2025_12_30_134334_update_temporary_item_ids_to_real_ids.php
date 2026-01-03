<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Trip;
use App\Models\TripDocument;

return new class extends Migration
{
    /**
     * Run the migrations - Update temporary item IDs to real item IDs
     */
    public function up(): void
    {
        // Get all trips and update documents with temporary item IDs
        $trips = Trip::all();

        foreach ($trips as $trip) {
            if (!$trip->items_data) {
                continue;
            }

            // Get all documents for this trip with temporary item IDs
            $tempDocuments = TripDocument::where('trip_id', $trip->id)
                ->where('item_id', 'like', 'temp_%')
                ->get();

            foreach ($tempDocuments as $document) {
                // Try to find the corresponding item based on type
                // Get all items of this type
                $matchingItems = array_filter($trip->items_data, function($item) use ($document) {
                    return isset($item['type']) && $item['type'] === $document->type && isset($item['id']);
                });

                // If we find matching items, assign to the first one
                if (count($matchingItems) > 0) {
                    $itemId = reset($matchingItems)['id'];
                    $document->update(['item_id' => $itemId]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all real item IDs back to temporary format
        // This is generally not advisable, but included for completeness
        // We'll mark them as rolled back instead
        TripDocument::where('item_id', '!=', null)
            ->where('item_id', 'not like', 'temp_%')
            ->update(['item_id' => null]);    }
};