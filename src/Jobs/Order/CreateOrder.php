<?php

namespace BusinessCentral\Jobs\Order;


use BusinessCentral\API\HttpClient;
use BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Pionect\Backoffice\Models\Order\Order;

/**
 * Class CreateOrder
 *
 * @package BusinessCentral\Jobs\Order
 */
class CreateOrder implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    /**
     * @var \Pionect\Backoffice\Models\Order\Order
     */
    protected $order;

    /**
     * @var \BusinessCentral\Repositories\ReferenceRepository
     */
    protected $referenceRepository;

    public function __construct(Order $order, ReferenceRepository $referenceRepository)
    {
        $this->queue               = 'high';
        $this->order               = $order;
        $this->referenceRepository = $referenceRepository;
    }

    public function handle()
    {
        /**
         * @var HttpClient $client
         */
        $client = app(HttpClient::class);
        $client->salesOrder()->create($this->order);
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['business-central', 'create-order', 'order', 'order-'.$this->order->id];
    }
}
