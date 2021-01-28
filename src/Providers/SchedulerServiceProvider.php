<?php

namespace BusinessCentral\Providers;

use BusinessCentral\Commands\BusinessCentralSubscription;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class SchedulerServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $scheduler = $this->app->make(Schedule::class);
            $scheduler->command(BusinessCentralSubscription::class, ['renew'])->daily();
        });
    }
}