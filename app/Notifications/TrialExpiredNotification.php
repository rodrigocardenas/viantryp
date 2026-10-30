<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialExpiredNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu prueba gratuita del Plan Viajero Pro ha finalizado — Viantryp')
            ->view('emails.trial-expired', [
                'name' => $this->user->name,
                'user_email' => $this->user->email,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'trial_expired',
            'icon' => 'fa-clock',
            'invite_url' => route('profile.index') . '?tab=subscription',
            'action_type' => 'upgrade_modal',
            'message' => 'Tu prueba gratuita del Plan Viajero Pro ha finalizado. Tus viajes siguen seguros en el Plan Básico.',
        ];
    }
}

