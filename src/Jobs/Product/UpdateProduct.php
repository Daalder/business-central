<?php

namespace BusinessCentral\Jobs\Product;

use BusinessCentral\API\HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pionect\Backoffice\Models\Product\Product;

class UpdateProduct implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /**
     * @var \Pionect\Backoffice\Models\Product\Product
     */
    protected $product;

    /**
     * @var \BusinessCentral\Repositories\ReferenceRepository
     */
    private $referenceRepository;

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
        $client->item()->update($this->product);
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['business-central', 'update-product', 'product', 'product-'.$this->product->id];
    }
}
