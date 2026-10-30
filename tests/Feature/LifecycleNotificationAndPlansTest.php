<?php

namespace Tests\Feature;

use App\Models\Trip;
use App\Models\User;
use App\Notifications\SubscriptionCanceledNotification;
use App\Notifications\SubscriptionConfirmedNotification;
use App\Notifications\TrialExpiredNotification;
use App\Notifications\TrialExpiringNotification;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LifecycleNotificationAndPlansTest extends TestCase
{
    public function test_official_plans_display_names_and_limits(): void
    {
        $basico = new User(['plan' => User::PLAN_BASICO, 'trial_ends_at' => null]);
        $this->assertSame('Básico', $basico->plan_display_name);
        $this->assertSame(1, $basico->getPlanLimits()['max_trips']);
        $this->assertSame(0, $basico->getPlanLimits()['max_editors']);
        $this->assertSame(5, $basico->getPlanLimits()['max_attachments']);

        $pro = new User(['plan' => User::PLAN_AVANZADO, 'trial_ends_at' => null]);
        $this->assertSame('Viajero Pro', $pro->plan_display_name);
        $this->assertSame(20, $pro->getPlanLimits()['max_trips']);
        $this->assertSame(2, $pro->getPlanLimits()['max_editors']);
        $this->assertSame(20, $pro->getPlanLimits()['max_attachments']);

        $negocios = new User(['plan' => User::PLAN_COLABORATIVO, 'trial_ends_at' => null]);
        $this->assertSame('Negocios', $negocios->plan_display_name);
        $this->assertGreaterThanOrEqual(1000000, $negocios->getPlanLimits()['max_trips']);

        // Legacy 'corporativo' maps cleanly to 'Negocios'
        $legacyCorp = new User(['plan' => User::PLAN_CORPORATIVO, 'trial_ends_at' => null]);
        $this->assertSame('Negocios', $legacyCorp->plan_display_name);
    }

    public function test_google_places_limits_match_plan_tiers(): void
    {
        $fivePlacesState = [
            'days' => [[
                ['place_id' => 'p1'],
                ['place_id' => 'p2'],
                ['place_id' => 'p3'],
                ['place_id' => 'p4'],
                ['place_id' => 'p5'],
            ]],
        ];

        $trip = new Trip(['pro_state' => $fivePlacesState]);

        $basicoUser = new User(['plan' => User::PLAN_BASICO, 'trial_ends_at' => null]);
        $proUser = new User(['plan' => User::PLAN_AVANZADO, 'trial_ends_at' => null]);
        $negociosUser = new User(['plan' => User::PLAN_COLABORATIVO, 'trial_ends_at' => null]);

        $this->assertTrue($trip->hasReachedGooglePlacesLimit($basicoUser));
        $this->assertFalse($trip->hasReachedGooglePlacesLimit($proUser));
        $this->assertFalse($trip->hasReachedGooglePlacesLimit($negociosUser));
    }

    public function test_lifecycle_notifications_build_mail_and_database_payloads(): void
    {
        $user = new User([
            'name' => 'Camila Viajera',
            'email' => 'camila@viantryp.com',
            'plan' => User::PLAN_AVANZADO,
            'trial_ends_at' => Carbon::now()->addDays(3),
        ]);

        // 1. 3-day trial expiring notification
        $expiring = new TrialExpiringNotification($user, 3);
        $this->assertSame(['mail', 'database'], $expiring->via($user));
        $expiringData = $expiring->toArray($user);
        $this->assertSame('trial_expiring', $expiringData['type']);
        $this->assertStringContainsString('3 días', $expiringData['message']);
        $this->assertStringContainsString('Plan Viajero Pro', $expiringData['message']);
        $mailExpiring = $expiring->toMail($user);
        $this->assertStringContainsString('Viajero Pro', $mailExpiring->subject);

        // 2. Trial expired notification
        $expired = new TrialExpiredNotification($user);
        $this->assertSame(['mail', 'database'], $expired->via($user));
        $expiredData = $expired->toArray($user);
        $this->assertSame('trial_expired', $expiredData['type']);
        $this->assertStringContainsString('Plan Básico', $expiredData['message']);

        // 3. Subscription confirmed notification (Viajero Pro & Negocios)
        $confirmed = new SubscriptionConfirmedNotification($user, 'Negocios');
        $this->assertSame(['mail', 'database'], $confirmed->via($user));
        $confirmedData = $confirmed->toArray($user);
        $this->assertSame('subscription_confirmed', $confirmedData['type']);
        $this->assertStringContainsString('Plan Negocios', $confirmedData['message']);

        // 4. Subscription canceled with grace period notification
        $canceled = new SubscriptionCanceledNotification($user, 'Viajero Pro', '30/11/2026');
        $this->assertSame(['mail', 'database'], $canceled->via($user));
        $canceledData = $canceled->toArray($user);
        $this->assertSame('subscription_canceled', $canceledData['type']);
        $this->assertStringContainsString('30/11/2026', $canceledData['message']);
    }
}
