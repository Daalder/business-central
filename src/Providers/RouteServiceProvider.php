<?php

namespace BusinessCentral\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $namespace = "BusinessCentral\Controllers";

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapBusinessCentralWebRoutes();
        $this->mapBusinessCentralAPIRoutes();
    }

    protected function mapBusinessCentralWebRoutes()
    {
        $middleware = hook('api-unauthenticated-middleware', ['web', 'global_view_shares']);

        Route::middleware($middleware)
            ->namespace($this->namespace)
            ->prefix('business-central')
            ->group(__DIR__.'/../../routes/web.php');
    }

    protected function mapBusinessCentralAPIRoutes()
    {
        $middleware = hook('api-authenticated-middleware', ['api', 'api_log_request']);

        Route::middleware($middleware)
            ->namespace($this->namespace)
            ->prefix('business-central')
            ->group(__DIR__.'/../../routes/api.php');
    }
}
