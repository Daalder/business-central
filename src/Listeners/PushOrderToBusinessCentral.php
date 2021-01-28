<?php

namespace BusinessCentral\Listeners;

use BusinessCentral\API\HttpClient;
use BusinessCentral\Jobs\Order\CreateOrder;
use BusinessCentral\Repositories\ReferenceRepository;
use Pionect\Backoffice\Events\Order\OrderCreated;
use Pionect\Backoffice\Events\Payment\PaymentUpdated;
use Pionect\Backoffice\Models\Payment\Payment;

class PushOrderToBusinessCentral
{
    /**
     * @var string
     */
    public $queue = 'high';

    /**
     * @var \BusinessCentral\API\HttpClient
     */
    private $client;

    /**
     * Create the event listener.
     *
     * @param \BusinessCentral\API\HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param \Pionect\Backoffice\Events\Order\OrderCreated $event
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function push($event)
    {
        if (optional($event->order->payment)->status == Payment::OK) {
            dispatch(new CreateOrder($event->order, app(ReferenceRepository::class)));
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            OrderCreated::class,
            'BusinessCentral\Listeners\PushOrderToBusinessCentral@push'
        );

        $events->listen(
            PaymentUpdated::class,
            'BusinessCentral\Listeners\PushOrderToBusinessCentral@push'
        );
    }


    public function tags()
    {
        return ['business-central', 'order'];
    }
}
