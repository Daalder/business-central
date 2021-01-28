<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Providers;

use Daalder\BusinessCentral\API\Observers\SubscriptionNoticeObserver;
use Daalder\BusinessCentral\API\Observers\SubscriptionObserver;
use Daalder\BusinessCentral\Models\Subscription;
use Daalder\BusinessCentral\Models\SubscriptionNotice;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(): void
    {
        Subscription::observe(SubscriptionObserver::class);
        SubscriptionNotice::observe(SubscriptionNoticeObserver::class);
    }
}
