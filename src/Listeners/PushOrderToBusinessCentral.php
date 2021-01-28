<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Listeners;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Jobs\Order\CreateOrder;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Pionect\Backoffice\Events\Order\OrderCreated;
use Pionect\Backoffice\Events\Payment\PaymentUpdated;
use Pionect\Backoffice\Models\Payment\Payment;

class PushOrderToBusinessCentral
{
    public string $queue = 'high';

    private \BusinessCentral\API\HttpClient $client;

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
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function push(\Pionect\Backoffice\Events\Order\OrderCreated $event): void
    {
        if (optional($event->order->payment)->status === Payment::OK) {
            dispatch(new CreateOrder($event->order, app(ReferenceRepository::class)));
        }
    }

    public function subscribe($events): void
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
