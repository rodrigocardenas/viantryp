<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\User;

class DiscordService
{
    public function sendUserRegistrationNotification(User $user)
    {
        $webhookUrl = config('services.discord.webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $embed = [
            'title' => '🚀 ¡Nuevo Registro en Viantryp!',
            'description' => "Se ha unido un nuevo aventurero a la plataforma.",
            'color' => 0x1A7A8A, // Color corporativo de Viantryp (#1A7A8A)
            'fields' => [
                [
                    'name' => '👤 Nombre',
                    'value' => $user->name,
                    'inline' => true,
                ],
                [
                    'name' => '📧 Email',
                    'value' => $user->email,
                    'inline' => true,
                ],
                [
                    'name' => '🌍 País',
                    'value' => $user->country ?? 'No especificado',
                    'inline' => true,
                ],
                [
                    'name' => '💎 Plan Seleccionado',
                    'value' => "```" . mb_strtoupper($user->plan ?? 'básico') . "```",
                    'inline' => false,
                ],
            ],
            'footer' => [
                'text' => 'Viantryp Registration Bot • ID: ' . $user->id,
            ],
            'timestamp' => now()->toIso8601String(),
        ];

        Http::post($webhookUrl, [
            'embeds' => [$embed],
        ]);
    }
}
