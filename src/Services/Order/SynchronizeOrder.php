<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Services\Order;

use Daalder\BusinessCentral\Jobs\Customer\CreateCustomer;
use Daalder\BusinessCentral\Jobs\Order\CreateOrder;
use Daalder\BusinessCentral\Jobs\Order\CreateSalesQuote;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Pionect\Daalder\Models\Order\Order;
use Pionect\Daalder\Models\Order\State;

class SynchronizeOrder
{
    private ReferenceRepository $referenceRepository;

    /**
     * SynchronizeOrder constructor.
     */
    public function __construct(ReferenceRepository $referenceRepository)
    {
        $this->referenceRepository = $referenceRepository;
    }

    public function sync(Order $order): void
    {
        if ($order->state()->exists()) {
            switch ($order->state->name) {
                case State::CONCEPT:
                    CreateSalesQuote::withChain([
                        new CreateCustomer($order->customer, $this->referenceRepository),
                    ])->dispatch($order, $this->referenceRepository);
                    break;
                default:
                    CreateOrder::withChain([
                        new CreateCustomer($order->customer, $this->referenceRepository),
                    ])->dispatch($order, $this->referenceRepository);
                    break;
            }
        }
    }
}
