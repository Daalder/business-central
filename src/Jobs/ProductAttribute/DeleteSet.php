<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\ProductAttribute;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\SetBusinessCentral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Pionect\Daalder\Models\ProductAttribute\Set;

/**
 * Class DeleteSet
 *
 * @package BusinessCentral\Jobs\ProductAttribute
 */
class DeleteSet implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    protected $set;

    /**
     * Create a new job instance.
     */
    public function __construct(Set $set)
    {
        $this->set = $set;
    }

    public function handle(): void
    {
        /** @var HttpClient $client */
        $client = App::make(HttpClient::class);

        $resource = new \BusinessCentral\API\Resources\ItemCategory($this->set);
        $client->itemCategory()->delete($resource->resolve());

        // Remove reference
        SetBusinessCentral::where('productattributeset_id', $this->set->id)->delete();
    }
}
