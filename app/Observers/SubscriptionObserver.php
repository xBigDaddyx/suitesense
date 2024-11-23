<?php

namespace App\Observers;

use App\Models\Vendor\Subscription;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        $subscription->licenses()->create([
            'customer_id' => $subscription->customer_id,
            'key' => \App\Models\Vendor\License::generateLicenseKey(),
            'type' => 'production',
            'is_active' => true,
            'expires_at' => $subscription->ends_at,
            'subscription_id' => $subscription->id,
        ]);
    }

    /**
     * Handle the Subscription "updated" event.
     */
    public function updated(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "deleted" event.
     */
    public function deleted(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "restored" event.
     */
    public function restored(Subscription $subscription): void
    {
        //
    }

    /**
     * Handle the Subscription "force deleted" event.
     */
    public function forceDeleted(Subscription $subscription): void
    {
        //
    }
}
