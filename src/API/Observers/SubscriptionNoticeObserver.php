<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Observers;

use Daalder\BusinessCentral\API\Events\SubscriptionNotice\SubscriptionNoticeCreated;
use Daalder\BusinessCentral\Models\Subscription;

class SubscriptionNoticeObserver
{
    public function created(Subscription $subscription): void
    {
        event(SubscriptionNoticeCreated::class);
    }
}
