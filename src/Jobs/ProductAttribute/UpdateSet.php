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
 * Class UpdateSet
 *
 * @package BusinessCentral\Jobs\ProductAttribute
 */
class UpdateSet implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /** @var HttpClient $client */
    protected $client;
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

    /**
     * @return void
     */
    public function handle()
    {
        $this->client = App::make(HttpClient::class);

        $resource  = new \BusinessCentral\API\Resources\ItemCategory($this->set);
        $reference = SetBusinessCentral::where('productattributeset_id', $this->set->id)->first();


        if ($reference) {
            $array = $resource->resolve();
            unset($array['code']);
            $this->client->itemCategory()->update($array, $reference->business_central_id);
        } else {
            // if BC reference is not available try to create
            dispatch(new CreateSet($this->set));
        }

    }
}
