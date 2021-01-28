<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Observers;

use Daalder\BusinessCentral\API\Events\Subscription\SubscriptionCreated;
use Daalder\BusinessCentral\Models\Subscription;

class SubscriptionObserver
{
    /**
     * Fire SubscriptionCreated event.
     */
    public function created(Subscription $subscription): void
    {
        event(new SubscriptionCreated($subscription));
    }
}
