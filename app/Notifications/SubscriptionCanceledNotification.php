<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCanceledNotification extends Notification
{
    use Queueable;

    protected $user;
    protected string $planName;
    protected ?string $endsAtFormatted;

    public function __construct($user, string $planName = 'Viajero Pro', ?string $endsAtFormatted = null)
    {
        $this->user = $user;
        $this->planName = $planName;
        $this->endsAtFormatted = $endsAtFormatted;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Confirmación de cancelación de tu Plan {$this->planName} — Viantryp")
            ->view('emails.subscription-canceled', [
                'name' => $this->user->name,
                'user_email' => $this->user->email,
                'plan_name' => $this->planName,
                'ends_at' => $this->endsAtFormatted,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        $graceText = $this->endsAtFormatted
            ? " Conservas todos tus beneficios hasta el {$this->endsAtFormatted}."
            : ' Tus viajes siguen seguros en el Plan Básico.';

        return [
            'type' => 'subscription_canceled',
            'icon' => 'fa-calendar-check',
            'plan_name' => $this->planName,
            'ends_at' => $this->endsAtFormatted,
            'invite_url' => route('profile.index') . '?tab=subscription',
            'message' => "Se ha confirmado la cancelación de tu Plan {$this->planName}.{$graceText}",
        ];
    }
}

