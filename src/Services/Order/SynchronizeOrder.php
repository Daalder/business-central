<?php

namespace BusinessCentral\Services\Order;

use BusinessCentral\Jobs\Customer\CreateCustomer;
use BusinessCentral\Jobs\Order\CreateOrder;
use BusinessCentral\Jobs\Order\CreateSalesQuote;
use BusinessCentral\Repositories\ReferenceRepository;
use Pionect\Backoffice\Models\Order\Order;
use Pionect\Backoffice\Models\Order\State;

class SynchronizeOrder
{
    /**
     * @var ReferenceRepository
     */
    private $referenceRepository;

    /**
     * SynchronizeOrder constructor.
     *
     * @param  ReferenceRepository  $referenceRepository
     */
    public function __construct(ReferenceRepository $referenceRepository)
    {
        $this->referenceRepository = $referenceRepository;
    }

    /**
     * @param  Order  $order
     */
    public function sync(Order $order)
    {
        if ($order->state()->exists()) {
            switch ($order->state->name) {
                case State::CONCEPT:
                    CreateSalesQuote::withChain([
                        new CreateCustomer($order->customer, $this->referenceRepository)
                    ])->dispatch($order, $this->referenceRepository);
                    break;
                default:
                    CreateOrder::withChain([
                        new CreateCustomer($order->customer, $this->referenceRepository)
                    ])->dispatch($order, $this->referenceRepository);
                    break;
            }
        }
    }
}
