<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionConfirmedNotification extends Notification
{
    use Queueable;

    protected $user;
    protected string $planName;

    public function __construct($user, string $planName = 'Viajero Pro')
    {
        $this->user = $user;
        $this->planName = $planName;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("🎉 ¡Tu Plan {$this->planName} está activo! — Viantryp")
            ->view('emails.subscription-confirmed', [
                'name' => $this->user->name,
                'user_email' => $this->user->email,
                'plan_name' => $this->planName,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'subscription_confirmed',
            'icon' => 'fa-gem',
            'plan_name' => $this->planName,
            'invite_url' => route('profile.index') . '?tab=subscription',
            'message' => "¡Tu Plan {$this->planName} ha sido activado exitosamente! Ya tienes todos tus beneficios disponibles.",
        ];
    }
}

