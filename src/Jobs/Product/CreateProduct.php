<?php

namespace BusinessCentral\Jobs\Product;

use BusinessCentral\API\HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pionect\Backoffice\Models\Product\Product;

/**
 * Class CreateProduct
 *
 * @package BusinessCentral\Jobs\Product
 */
class CreateProduct implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /**
     * @var \Pionect\Backoffice\Models\Product\Product
     */
    protected $product;

    /**
     * @var \BusinessCentral\Repositories\ReferenceRepository
     */
    protected $referenceRepository;

    /**
     * Create a new job instance.
     *
     * @param  \Pionect\Backoffice\Models\Product\Product  $product
     */
    public function __construct(Product $product)
    {
        $this->queue   = 'medium';
        $this->product = $product;
    }

    public function handle()
    {
        $client = app(HttpClient::class);
        $client->item()->create($this->product);
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['business-central', 'create-product', 'product', 'product-'.$this->product->id];
    }
}
