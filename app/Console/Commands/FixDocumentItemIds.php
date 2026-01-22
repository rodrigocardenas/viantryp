<?php

namespace App\Console\Commands;

use App\Models\Trip;
use App\Models\TripDocument;
use Illuminate\Console\Command;

class FixDocumentItemIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-document-item-ids {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix documents that don\'t have item_id by assigning them to the first item of the same type in each day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $trips = Trip::with('documents')->get();
        $totalFixed = 0;

        foreach ($trips as $trip) {
            if (!$trip->items_data) {
                continue;
            }

            $documentsWithoutItemId = $trip->documents->whereNull('item_id');

            foreach ($documentsWithoutItemId as $document) {
                // Find the first item of the same type
                $matchingItem = null;
                foreach ($trip->items_data as $item) {
                    if (isset($item['type']) && $item['type'] === $document->type && isset($item['id'])) {
                        $matchingItem = $item;
                        break;
                    }
                }

                if ($matchingItem) {
                    $this->line("Trip {$trip->id}: Assigning document '{$document->original_name}' (type: {$document->type}) to item '{$matchingItem['id']}'");

                    if (!$dryRun) {
                        $document->update(['item_id' => $matchingItem['id']]);
                    }

                    $totalFixed++;
                } else {
                    $this->warn("Trip {$trip->id}: No matching item found for document '{$document->original_name}' (type: {$document->type})");
                }
            }
        }

        if ($dryRun) {
            $this->info("DRY RUN COMPLETE: Would fix {$totalFixed} documents");
        } else {
            $this->info("FIXED: {$totalFixed} documents have been assigned item_ids");
        }
    }
}
