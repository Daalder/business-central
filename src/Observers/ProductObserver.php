<?php

namespace BusinessCentral\Observers;

use BusinessCentral\Jobs\Product\CreateProduct;
use BusinessCentral\Jobs\Product\UpdateProduct;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Pionect\Backoffice\Models\Product\Product;

class ProductObserver
{
    use DispatchesJobs;

    public function updated(Product $product)
    {
        dispatch(new UpdateProduct($product));
    }

    /**
     * Listen to the Product created event.
     *
     * @param  \Pionect\Backoffice\Models\Product\Product  $product
     */
    public function created(Product $product)
    {
//        dispatch(new CreateProduct($product));
    }

    /**
     * Listen to the Product deleted event.
     *
     * @param  \Pionect\Backoffice\Models\Product\Product  $product
     */
    public function deleted(Product $product)
    {
        // dispatch(new DeleteProduct($product));
    }

}
