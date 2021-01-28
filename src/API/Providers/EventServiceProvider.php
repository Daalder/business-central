<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Providers;

use Daalder\BusinessCentral\API\Events\Subscription\SubscriptionCreated;
use Daalder\BusinessCentral\API\Events\SubscriptionNotice\SubscriptionNoticeCreated;
use Daalder\BusinessCentral\API\Listeners\Subscription\BusinessCentralSubscriptionRegister;
use Daalder\BusinessCentral\API\Listeners\SubscriptionNotice\SubscriptionNoticeProcess;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected array $listen = [
        SubscriptionCreated::class => [
            BusinessCentralSubscriptionRegister::class,
        ],

        SubscriptionNoticeCreated::class => [
            SubscriptionNoticeProcess::class,
        ],
    ];
}
