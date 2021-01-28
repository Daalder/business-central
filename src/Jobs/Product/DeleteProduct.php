<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Product;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
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

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function handle(): void
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
    public function tags(): array
    {
        return ['business-central', 'delete-product', 'product', 'product-'.$this->product->id];
    }
}
