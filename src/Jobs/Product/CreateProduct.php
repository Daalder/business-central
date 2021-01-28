<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Product;

use Daalder\BusinessCentral\API\HttpClient;
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

    protected \Pionect\Backoffice\Models\Product\Product $product;

    protected \BusinessCentral\Repositories\ReferenceRepository $referenceRepository;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product)
    {
        $this->queue = 'medium';
        $this->product = $product;
    }

    public function handle(): void
    {
        $client = app(HttpClient::class);
        $client->item()->create($this->product);
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return ['business-central', 'create-product', 'product', 'product-'.$this->product->id];
    }
}
