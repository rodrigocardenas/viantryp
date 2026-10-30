<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Notifications\TrialExpiringNotification;
use App\Notifications\TrialExpiredNotification;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('viantryp:lifecycle-notifications', function () {
    $expiringSent = 0;
    $expiredSent = 0;

    // 1. Usuarios con prueba gratuita que vence en <= 3 días (entre hoy y 3 días)
    $expiringUsers = User::whereNotNull('trial_ends_at')
        ->where('trial_ends_at', '>', now())
        ->where('trial_ends_at', '<=', now()->addDays(3)->endOfDay())
        ->get();

    foreach ($expiringUsers as $user) {
        try {
            if (Schema::hasTable('subscriptions') && $user->subscriptions()->valid()->exists()) {
                continue;
            }

            $alreadyNotified = $user->notifications()
                ->where('type', TrialExpiringNotification::class)
                ->where('created_at', '>=', now()->subDays(15))
                ->exists();

            if (!$alreadyNotified) {
                $days = max(1, (int) ceil(now()->floatDiffInDays($user->trial_ends_at, false)));
                $user->notify(new TrialExpiringNotification($user, $days));
                $expiringSent++;
            }
        } catch (\Throwable $e) {
            \Log::warning("Error enviando TrialExpiringNotification a user {$user->id}: " . $e->getMessage());
        }
    }

    // 2. Usuarios cuya prueba gratuita ya venció
    $expiredUsers = User::whereNotNull('trial_ends_at')
        ->where('trial_ends_at', '<=', now())
        ->get();

    foreach ($expiredUsers as $user) {
        try {
            if (Schema::hasTable('subscriptions') && $user->subscriptions()->valid()->exists()) {
                continue;
            }

            if ($user->plan !== User::PLAN_BASICO) {
                $user->update(['plan' => User::PLAN_BASICO]);
            }

            $alreadyNotified = $user->notifications()
                ->where('type', TrialExpiredNotification::class)
                ->exists();

            if (!$alreadyNotified) {
                $user->notify(new TrialExpiredNotification($user));
                $expiredSent++;
            }
        } catch (\Throwable $e) {
            \Log::warning("Error procesando TrialExpiredNotification para user {$user->id}: " . $e->getMessage());
        }
    }

    $this->info("Notificaciones procesadas: {$expiringSent} por vencer (<=3 días), {$expiredSent} vencidas.");
})->purpose('Envía notificaciones por correo e in-app de vencimiento de prueba gratuita (faltando 3 días y al vencer)');

Schedule::command('viantryp:lifecycle-notifications')->dailyAt('09:00');
