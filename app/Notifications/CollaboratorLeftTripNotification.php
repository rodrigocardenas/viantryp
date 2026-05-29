<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CollaboratorLeftTripNotification extends Notification
{
    use Queueable;

    protected $trip;
    protected $collaborator;

    /**
     * Create a new notification instance.
     */
    public function __construct($trip, $collaborator)
    {
        $this->trip = $trip;
        $this->collaborator = $collaborator;
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
            'collaborator_id' => $this->collaborator->id,
            'collaborator_name' => $this->collaborator->display_name,
            'invite_url' => route('trips.index'),
            'message' => "{$this->collaborator->display_name} ha dejado de colaborar en tu viaje \"{$this->trip->title}\".",
        ];
    }
}
