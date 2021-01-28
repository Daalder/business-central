<?php

namespace BusinessCentral\Providers;

use BusinessCentral\Listeners\PushOrderToBusinessCentral;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [

    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        PushOrderToBusinessCentral::class
    ];

    /**
     * Register any other events for your application.
     *
     */
    public function boot()
    {
        parent::boot();
    }
}
