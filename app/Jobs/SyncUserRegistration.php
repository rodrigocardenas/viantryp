<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\DiscordService;
use App\Services\GoogleSheetService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncUserRegistration implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(DiscordService $discord, GoogleSheetService $googleSheets): void
    {
        // 1. Enviar a Discord
        try {
            $discord->sendUserRegistrationNotification($this->user);
        } catch (\Exception $e) {
            \Log::error('Error in Discord Notification Job: ' . $e->getMessage());
        }

        // 2. Sincronizar con Google Sheets
        try {
            $googleSheets->appendUser($this->user);
        } catch (\Exception $e) {
            \Log::error('Error in Google Sheets Sync Job: ' . $e->getMessage());
        }
    }
}
