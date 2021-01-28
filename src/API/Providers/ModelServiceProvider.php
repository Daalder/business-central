<?php

namespace BusinessCentral\API\Providers;

use BusinessCentral\API\Observers\SubscriptionNoticeObserver;
use BusinessCentral\API\Observers\SubscriptionObserver;
use BusinessCentral\Models\Subscription;
use BusinessCentral\Models\SubscriptionNotice;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function boot()
    {
        Subscription::observe(SubscriptionObserver::class);
        SubscriptionNotice::observe(SubscriptionNoticeObserver::class);
    }
}