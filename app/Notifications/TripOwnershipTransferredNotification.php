<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TripOwnershipTransferredNotification extends Notification
{
    use Queueable;

    protected $trip;
    protected $oldOwner;

    /**
     * Create a new notification instance.
     */
    public function __construct($trip, $oldOwner)
    {
        $this->trip = $trip;
        $this->oldOwner = $oldOwner;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'trip_id' => $this->trip->id,
            'trip_title' => $this->trip->title,
            'old_owner_id' => $this->oldOwner->id,
            'old_owner_name' => $this->oldOwner->display_name,
            'invite_url' => route('trips.index', ['filter' => 'my-trips']),
            'message' => "{$this->oldOwner->display_name} te ha transferido la propiedad del viaje \"{$this->trip->title}\".",
        ];
    }
}
