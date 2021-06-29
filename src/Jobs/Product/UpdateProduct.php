<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Product;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pionect\Daalder\Models\Product\Product;

class UpdateProduct implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    protected Product $product;

    private ReferenceRepository $referenceRepository;

    /**
     * Create a new job instance.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->queue = 'medium';
        $this->product = $product;
    }

    public function handle(): void
    {
        $client = app(HttpClient::class);
        $client->item()->update($this->product);
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return ['business-central', 'update-product', 'product', 'product-'.$this->product->id];
    }
}
