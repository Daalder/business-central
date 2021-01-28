<?php

namespace BusinessCentral\Jobs\ProductAttribute;

use BusinessCentral\API\HttpClient;
use BusinessCentral\Models\SetBusinessCentral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Pionect\Backoffice\Models\ProductAttribute\Set;

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
     *
     * @param  \Pionect\Backoffice\Models\ProductAttribute\Set  $set
     */
    public function __construct(Set $set)
    {
        $this->set = $set;
    }

    public function handle()
    {
        /** @var HttpClient $client */
        $client = App::make(HttpClient::class);

        $resource = new \BusinessCentral\API\Resources\ItemCategory($this->set);
        $client->itemCategory()->delete($resource->resolve());

        // Remove reference
        SetBusinessCentral::where('productattributeset_id', $this->set->id)->delete();
    }
}
