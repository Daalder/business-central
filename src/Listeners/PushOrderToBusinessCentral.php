<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Listeners;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Jobs\Order\CreateOrder;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Pionect\Backoffice\Events\Order\OrderCreated;
use Pionect\Backoffice\Events\Order\OrderPaymentConfirmed;
use Pionect\Backoffice\Events\Payment\PaymentUpdated;
use Pionect\Backoffice\Models\Payment\Payment;

class PushOrderToBusinessCentral
{
    public string $queue = 'high';

    private HttpClient $client;

    /**
     * Create the event listener.
     *
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param OrderPaymentConfirmed $event
     */
    public function handle(OrderPaymentConfirmed $event)
    {
        dispatch(new CreateOrder($event->order, app(ReferenceRepository::class)));
    }

    public function tags()
    {
        return ['business-central', 'order'];
    }
}
