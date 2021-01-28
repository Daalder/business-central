<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Observers;

use Daalder\BusinessCentral\Jobs\Product\UpdateProduct;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pionect\Backoffice\Models\Product\Product;

class ProductObserver
{
    use DispatchesJobs;

    public function updated(Product $product): void
    {
        dispatch(new UpdateProduct($product));
    }

    /**
     * Listen to the Product created event.
     */
    public function created(Product $product): void
    {
//        dispatch(new CreateProduct($product));
    }

    /**
     * Listen to the Product deleted event.
     */
    public function deleted(Product $product): void
    {
        // dispatch(new DeleteProduct($product));
    }
}
