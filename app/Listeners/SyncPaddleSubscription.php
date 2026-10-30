<?php

namespace App\Listeners;

use Laravel\Paddle\Events\WebhookReceived;
use Laravel\Paddle\Events\SubscriptionCreated;
use Laravel\Paddle\Events\SubscriptionUpdated;
use Laravel\Paddle\Events\SubscriptionCanceled;
use Laravel\Paddle\Cashier;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class SyncPaddleSubscription
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        if ($event instanceof WebhookReceived) {
            $this->ensureCustomerLinkedFromPayload($event->payload ?? []);
            return;
        }

        $subscription = $event->subscription ?? null;
        if (!$subscription) {
            return;
        }

        $billable = $subscription->billable;
        if ((!$billable || !($billable instanceof User)) && isset($event->payload['data']['custom_data']['user_id'])) {
            $billable = User::find($event->payload['data']['custom_data']['user_id']);
        }

        if (!$billable || !($billable instanceof User)) {
            return;
        }

        // Keep user on their paid plan while valid() or onGracePeriod() is true.
        // Only downgrade to Básico when the subscription is no longer valid and grace period has ended.
        if ($event instanceof SubscriptionCanceled) {
            $currentDisplayName = $billable->plan_display_name;
            $endsAtFormatted = ($subscription->onGracePeriod() && $subscription->ends_at)
                ? \Carbon\Carbon::parse($subscription->ends_at)->format('d/m/Y')
                : null;

            if (!$subscription->onGracePeriod()) {
                $billable->update(['plan' => User::PLAN_BASICO]);
            }

            try {
                $alreadySent = $billable->notifications()
                    ->where('type', \App\Notifications\SubscriptionCanceledNotification::class)
                    ->where('created_at', '>=', now()->subMinutes(10))
                    ->exists();
                if (!$alreadySent) {
                    $billable->notify(new \App\Notifications\SubscriptionCanceledNotification($billable, $currentDisplayName, $endsAtFormatted));
                }
            } catch (\Throwable $e) {
                \Log::warning('SubscriptionCanceledNotification error: ' . $e->getMessage());
            }
            return;
        }

        if (!$subscription->valid() && !$subscription->onGracePeriod()) {
            $billable->update(['plan' => User::PLAN_BASICO]);
            return;
        }

        // Retrieve price ID from subscription item
        $item = $subscription->items()->first();
        $priceId = $item ? $item->price_id : null;

        if (!$priceId) {
            return;
        }

        $paddlePrices = config('plans.paddle', []);
        $matchedPlan = User::PLAN_BASICO;

        foreach ($paddlePrices as $planKey => $prices) {
            if (($prices['monthly'] ?? '') === $priceId || ($prices['annual'] ?? '') === $priceId) {
                $matchedPlan = $planKey;
                break;
            }
        }

        $previousPlan = $billable->plan;
        $hadTrial = $billable->trial_ends_at !== null;

        $billable->update([
            'plan' => $matchedPlan,
            'trial_ends_at' => null,
        ]);

        if ($matchedPlan !== User::PLAN_BASICO && ($event instanceof SubscriptionCreated || $previousPlan !== $matchedPlan || $hadTrial)) {
            try {
                $alreadySent = $billable->notifications()
                    ->where('type', \App\Notifications\SubscriptionConfirmedNotification::class)
                    ->where('created_at', '>=', now()->subMinutes(10))
                    ->exists();
                if (!$alreadySent) {
                    $billable->refresh();
                    $billable->notify(new \App\Notifications\SubscriptionConfirmedNotification($billable, $billable->plan_display_name));
                }
            } catch (\Throwable $e) {
                \Log::warning('SubscriptionConfirmedNotification error: ' . $e->getMessage());
            }
        }
    }

    /**
     * Ensure a Cashier Customer row exists when Paddle sends custom_data.user_id
     * from a client-side Paddle.Checkout.open() call.
     */
    protected function ensureCustomerLinkedFromPayload(array $payload): void
    {
        try {
            $data = $payload['data'] ?? [];
            $customerId = $data['customer_id'] ?? null;
            $userId = $data['custom_data']['user_id'] ?? null;

            if (!$customerId || !$userId || !Schema::hasTable('customers')) {
                return;
            }

            if (Cashier::findBillable($customerId)) {
                return;
            }

            $user = User::find($userId);
            if (!$user) {
                return;
            }

            $user->customer()->updateOrCreate(
                ['billable_id' => $user->id, 'billable_type' => $user->getMorphClass()],
                [
                    'paddle_id' => $customerId,
                    'name' => $user->name ?? '',
                    'email' => $user->email,
                ]
            );
        } catch (\Throwable $e) {
            \Log::warning('Paddle customer auto-link notice: ' . $e->getMessage());
        }
    }
}
