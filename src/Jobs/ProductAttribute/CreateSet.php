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
 * Class CreateSet
 *
 * @package BusinessCentral\Jobs\ProductAttribute
 */
class CreateSet implements ShouldQueue
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
