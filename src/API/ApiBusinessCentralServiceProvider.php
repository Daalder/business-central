<?php

namespace BusinessCentral\API;

use BusinessCentral\API\Providers\EventServiceProvider;
use BusinessCentral\API\Providers\ModelServiceProvider;
use Illuminate\Support\ServiceProvider;

class ApiBusinessCentralServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(ModelServiceProvider::class);
    }
}