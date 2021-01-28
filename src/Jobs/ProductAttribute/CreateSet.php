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
 * Class CreateSet
 *
 * @package BusinessCentral\Jobs\ProductAttribute
 */
class CreateSet implements ShouldQueue
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


    public function handle()
    {
        $this->client = App::make(HttpClient::class);

        $reference = SetBusinessCentral::where('productattributeset_id', $this->set->id)->first();

        if ($reference) {
            dispatch(new UpdateSet($this->set));
        } else {
            return $this->client->itemCategory()->create($this->set);
        }
    }
}
