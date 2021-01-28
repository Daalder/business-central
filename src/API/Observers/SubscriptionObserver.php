<?php

namespace BusinessCentral\API\Observers;

use BusinessCentral\API\Events\Subscription\SubscriptionCreated;
use BusinessCentral\Models\Subscription;

class SubscriptionObserver
{
    /**
     * Fire SubscriptionCreated event.
     *
     * @param Subscription $subscription
     */
    public function created(Subscription $subscription) {
        event(new SubscriptionCreated($subscription));
    }
}