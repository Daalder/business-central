<?php

namespace BusinessCentral\API\Observers;

use BusinessCentral\API\Events\SubscriptionNotice\SubscriptionNoticeCreated;
use BusinessCentral\Models\Subscription;

class SubscriptionNoticeObserver
{
    /**
     * @param Subscription $subscription
     */
    public function created(Subscription $subscription) {
        event(SubscriptionNoticeCreated::class);
    }
}
