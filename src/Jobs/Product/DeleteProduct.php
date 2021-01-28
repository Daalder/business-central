<?php

namespace BusinessCentral\Jobs\Product;

use BusinessCentral\API\HttpClient;
use BusinessCentral\Models\ProductBusinessCentral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Pionect\Backoffice\Models\Product\Product;

/**
 * Class DeleteProduct
 *
 * @package BusinessCentral\Jobs\Product
 */
class DeleteProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    /**
     * @param  \Pionect\Backoffice\Models\Product\Product  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle()
    {
        $client = App::make(HttpClient::class);

        $resource = new \BusinessCentral\API\Resources\Item($this->product);
        $client->item()->delete($resource->resolve());

        // Remove reference
        ProductBusinessCentral::where('productattributeset_id', $this->product->id)->delete();
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['business-central', 'delete-product', 'product', 'product-'.$this->product->id];
    }
}
