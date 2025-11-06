<?php

namespace App\Console\Commands;

use App\Models\Trip;
use Illuminate\Console\Command;

class GenerateTripCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trips:generate-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate unique codes for trips that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tripsWithoutCode = Trip::whereNull('code')->get();

        if ($tripsWithoutCode->isEmpty()) {
            $this->info('All trips already have codes.');
            return;
        }

        $this->info("Generating codes for {$tripsWithoutCode->count()} trips...");

        $progressBar = $this->output->createProgressBar($tripsWithoutCode->count());

        foreach ($tripsWithoutCode as $trip) {
            $trip->generateCode();
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info('All trip codes have been generated successfully!');
    }
}
