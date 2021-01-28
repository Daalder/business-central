<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API;

use Daalder\BusinessCentral\API\Providers\EventServiceProvider;
use Daalder\BusinessCentral\API\Providers\ModelServiceProvider;
use Illuminate\Support\ServiceProvider;

class ApiBusinessCentralServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(ModelServiceProvider::class);
    }
}
