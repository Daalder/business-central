<?php

namespace BusinessCentral\API\Providers;

use BusinessCentral\API\Events\Subscription\SubscriptionCreated;
use BusinessCentral\API\Events\SubscriptionNotice\SubscriptionNoticeCreated;
use BusinessCentral\API\Listeners\Subscription\BusinessCentralSubscriptionRegister;
use BusinessCentral\API\Listeners\SubscriptionNotice\SubscriptionNoticeProcess;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SubscriptionCreated::class => [
            BusinessCentralSubscriptionRegister::class
        ],

        SubscriptionNoticeCreated::class => [
            SubscriptionNoticeProcess::class
        ]
    ];
}
