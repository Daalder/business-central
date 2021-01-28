<?php

namespace BusinessCentral\Providers;

use BusinessCentral\Observers\ProductObserver;
use BusinessCentral\Observers\SubscriptionNoticeObserver;
use Illuminate\Support\ServiceProvider;
use Pionect\Backoffice\Models\Product\Product;

/**
 * Class ModelServiceProvider
 *
 * @package App\Providers
 */
class ModelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Product::observe(ProductObserver::class);
    }
}
