<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Order;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pionect\Backoffice\Models\Order\Order;

/**
 * Class CreateOrder
 *
 * @package BusinessCentral\Jobs\Order
 */
class CreateOrder implements ShouldQueue
{
    use Dispatchable, SerializesModels, Queueable, InteractsWithQueue;

    protected \Pionect\Backoffice\Models\Order\Order $order;

    protected Daalder\BusinessCentral\Repositories\ReferenceRepository $referenceRepository;

    public function __construct(Order $order, ReferenceRepository $referenceRepository)
    {
        $this->queue = 'high';
        $this->order = $order;
        $this->referenceRepository = $referenceRepository;
    }

    public function handle(): void
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
    public function tags(): array
    {
        return ['business-central', 'create-order', 'order', 'order-'.$this->order->id];
    }
}
