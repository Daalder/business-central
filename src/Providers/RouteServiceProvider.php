<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $namespace = "BusinessCentral\Controllers";

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $this->mapBusinessCentralWebRoutes();
        $this->mapBusinessCentralAPIRoutes();
    }

    protected function mapBusinessCentralWebRoutes(): void
    {
        $middleware = hook('api-unauthenticated-middleware', ['web', 'global_view_shares']);

        Route::middleware($middleware)
            ->namespace($this->namespace)
            ->prefix('business-central')
            ->group(__DIR__.'/../../routes/web.php');
    }

    protected function mapBusinessCentralAPIRoutes(): void
    {
        $middleware = hook('api-authenticated-middleware', ['api', 'api_log_request']);

        Route::middleware($middleware)
            ->namespace($this->namespace)
            ->prefix('business-central')
            ->group(__DIR__.'/../../routes/api.php');
    }
}
