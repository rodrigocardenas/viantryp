<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Public landing page returns HTTP 200 (or redirects to login in app mode).
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Dashboard route redirects to trips index, and protected profile route redirects guests to login.
     */
    public function test_dashboard_and_protected_routes_redirect_guests(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect(route('trips.index'));

        $this->get('/profile')->assertRedirect(route('home'));
    }

    /**
     * Planes redirect route sends guests to registration with upgrade intent.
     */
    public function test_planes_redirect_sends_guest_to_register_with_upgrade_intent(): void
    {
        $response = $this->get('/planes-redirect');

        $response->assertRedirect(route('register', ['intent' => 'upgrade']));
    }

    /**
     * Unauthenticated users cannot access protected client, airline, or places endpoints.
     */
    public function test_protected_endpoints_require_authentication(): void
    {
        $this->getJson('/persons/search?q=test')->assertStatus(401);
        $this->getJson('/api/places/details?place_id=ChIJN1t_tDeuEmsRUsoyG83frY4')->assertStatus(401);
        $this->postJson('/airlines', ['name' => 'Test Air'])->assertStatus(401);
        $this->postJson('/profile/plan/paddle-sync', ['plan' => 'pro'])->assertStatus(401);
    }

    /**
     * Image proxy blocks private/loopback IPs and non-HTTP schemes (SSRF protection).
     */
    public function test_image_proxy_blocks_ssrf_targets(): void
    {
        $this->get('/api/image-proxy?url=' . urlencode('http://127.0.0.1/secret'))
            ->assertStatus(403);

        $this->get('/api/image-proxy?url=' . urlencode('http://169.254.169.254/latest/meta-data/'))
            ->assertStatus(403);

        $this->get('/api/image-proxy?url=' . urlencode('file:///etc/passwd'))
            ->assertStatus(400);
    }

    /**
     * User trial expiration automatically falls back to Plan Básico when trial_ends_at is past.
     */
    public function test_expired_trial_falls_back_to_basico_plan(): void
    {
        $expiredTrialUser = new User([
            'plan' => User::PLAN_AVANZADO,
            'trial_ends_at' => Carbon::now()->subDay(),
        ]);

        $this->assertSame(User::PLAN_BASICO, $expiredTrialUser->effective_plan);
        $this->assertFalse($expiredTrialUser->isTrialActive());
        $this->assertSame(1, $expiredTrialUser->getPlanLimits()['max_trips']);

        $activeTrialUser = new User([
            'plan' => User::PLAN_BASICO,
            'trial_ends_at' => Carbon::now()->addDays(5),
        ]);

        $this->assertSame(User::PLAN_AVANZADO, $activeTrialUser->effective_plan);
        $this->assertTrue($activeTrialUser->isTrialActive());
        $this->assertSame(20, $activeTrialUser->getPlanLimits()['max_trips']);
    }
}

