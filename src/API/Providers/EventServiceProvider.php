<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Providers;

use Daalder\BusinessCentral\API\Events\Subscription\SubscriptionCreated;
use Daalder\BusinessCentral\API\Events\SubscriptionNotice\SubscriptionNoticeCreated;
use Daalder\BusinessCentral\API\Listeners\Subscription\BusinessCentralSubscriptionRegister;
use Daalder\BusinessCentral\API\Listeners\SubscriptionNotice\SubscriptionNoticeProcess;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package Daalder\BusinessCentral\API\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        SubscriptionCreated::class => [
            BusinessCentralSubscriptionRegister::class,
        ],

        SubscriptionNoticeCreated::class => [
            SubscriptionNoticeProcess::class,
        ],
    ];
}
