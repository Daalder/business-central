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
class CreateSalesQuote implements ShouldQueue
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

    /**
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function handle()
    {
        /**
         * @var HttpClient $client
         */
        $client = App::make(HttpClient::class);
        $client->salesQuote()->create($this->order);
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['business-central', 'create-sales-quote', 'order', 'order-'.$this->order->id];
    }
}
