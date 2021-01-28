<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Providers;

use Daalder\BusinessCentral\Commands\BusinessCentralSubscription;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class SchedulerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function (): void {
            $scheduler = $this->app->make(Schedule::class);
            $scheduler->command(BusinessCentralSubscription::class, ['renew'])->daily();
        });
    }
}
