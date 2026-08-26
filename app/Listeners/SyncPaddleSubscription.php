<?php

namespace App\Listeners;

use Laravel\Paddle\Events\SubscriptionCreated;
use Laravel\Paddle\Events\SubscriptionUpdated;
use Laravel\Paddle\Events\SubscriptionCanceled;
use App\Models\User;

class SyncPaddleSubscription
{
    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $subscription = $event->subscription ?? null;
        if (!$subscription) return;

        $billable = $subscription->billable;
        if (!$billable || !($billable instanceof User)) return;

        if ($event instanceof SubscriptionCanceled || !$subscription->active()) {
            $billable->update(['plan' => User::PLAN_BASICO]);
            return;
        }

        // Retrieve price ID from subscription item
        $item = $subscription->items()->first();
        $priceId = $item ? $item->price_id : null;

        if (!$priceId) return;

        $paddlePrices = config('plans.paddle', []);
        $matchedPlan = User::PLAN_BASICO;

        foreach ($paddlePrices as $planKey => $prices) {
            if (($prices['monthly'] ?? '') === $priceId || ($prices['annual'] ?? '') === $priceId) {
                $matchedPlan = $planKey;
                break;
            }
        }

        $billable->update([
            'plan' => $matchedPlan,
            'trial_ends_at' => null
        ]);
    }
}
