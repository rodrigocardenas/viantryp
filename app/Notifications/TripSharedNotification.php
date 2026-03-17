<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripSharedNotification extends Notification
{
    use Queueable;

    protected $trip;
    protected $sender;
    protected $role;
    protected $inviteUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($trip, $sender, $role, $inviteUrl)
    {
        $this->trip = $trip;
        $this->sender = $sender;
        $this->role = $role;
        $this->inviteUrl = $inviteUrl;
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
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->display_name,
            'role' => $this->role,
            'invite_url' => $this->inviteUrl,
            'message' => "{$this->sender->display_name} ha compartido el viaje \"{$this->trip->title}\" contigo.",
        ];
    }
}
