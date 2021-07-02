<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Providers;

use Daalder\BusinessCentral\Listeners\PushOrderToBusinessCentral;
use Daalder\BusinessCentral\Observers\ProductObserver;
use Daalder\BusinessCentral\Observers\SetObserver;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Pionect\Daalder\Events\Order\OrderPaymentConfirmed;
use Pionect\Daalder\Models\Product\Product;
use Pionect\Daalder\Models\ProductAttribute\Set;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected array $listen = [
        OrderPaymentConfirmed::class => [
            PushOrderToBusinessCentral::class,
        ],
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
        Product::observe(ProductObserver::class);
        Set::observe(SetObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return true;
    }
}
