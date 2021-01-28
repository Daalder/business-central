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
use Pionect\Backoffice\Models\ProductAttribute\Set;

/**
 * Class UpdateSet
 *
 * @package BusinessCentral\Jobs\ProductAttribute
 */
class UpdateSet implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    protected HttpClient $client;
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
        $this->client = App::make(HttpClient::class);

        $resource = new \BusinessCentral\API\Resources\ItemCategory($this->set);
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
