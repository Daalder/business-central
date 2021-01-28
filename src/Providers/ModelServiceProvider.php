<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Providers;

use Daalder\BusinessCentral\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;
use Pionect\Backoffice\Models\Product\Product;

/**
 * Class ModelServiceProvider
 *
 * @package Daalder\BusinessCentral\Providers
 */
class ModelServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Product::observe(ProductObserver::class);
    }
}
