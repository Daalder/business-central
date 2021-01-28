<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Providers;

use Daalder\BusinessCentral\Listeners\PushOrderToBusinessCentral;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected array $listen = [

    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected array $subscribe = [
        PushOrderToBusinessCentral::class,
    ];

    /**
     * Register any other events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
