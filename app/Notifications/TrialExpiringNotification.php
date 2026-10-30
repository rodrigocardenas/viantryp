<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrialExpiringNotification extends Notification
{
    use Queueable;

    protected $user;
    protected int $daysRemaining;

    public function __construct($user, int $daysRemaining = 3)
    {
        $this->user = $user;
        $this->daysRemaining = $daysRemaining;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("⏳ Te quedan {$this->daysRemaining} días de tu prueba gratuita del Plan Viajero Pro — Viantryp")
            ->view('emails.trial-expiring', [
                'name' => $this->user->name,
                'user_email' => $this->user->email,
                'days_remaining' => $this->daysRemaining,
                'trial_ends_at' => optional($this->user->trial_ends_at)->format('d/m/Y'),
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'trial_expiring',
            'icon' => 'fa-rocket',
            'days_remaining' => $this->daysRemaining,
            'invite_url' => route('profile.index') . '?tab=subscription',
            'action_type' => 'upgrade_modal',
            'message' => "Te quedan {$this->daysRemaining} días de tu prueba gratuita del Plan Viajero Pro. Mantén tus herramientas ilimitadas activas.",
        ];
    }
}

