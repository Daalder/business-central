<?php

namespace BusinessCentral\Repositories;

use App\Models\Orders\Order;
use BusinessCentral\Contracts\BusinessCentralApiResource;
use Pionect\Backoffice\Models\Order\Repositories\OrderRepository as BackOfficeOrderRepository;

class OrderRepository extends BackOfficeOrderRepository implements BusinessCentralApiResource
{
    /**
     * OrderRepository constructor.
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * Update resource from Business Central.
     *
     * @param array $items
     * @return bool
     */
    public function updateFromBusinessCentralApi(array $items = []): bool
    {
        // TODO: Implement updateFromBusinessCentralApi() method.
    }

    /**
     * Delete resource after Business Central.
     *
     * @param array $items
     * @return bool
     */
    public function deleteAfterBusinessCentralApi(array $items = []): bool
    {
        // TODO: Implement deleteAfterBusinessCentralApi() method.
    }

    /**
     * Create resource from Business Central.
     *
     * @param array $items
     * @return bool
     */
    public function createFromBusinessCentralApi(array $items = []): bool
    {
        // TODO: Implement createFromBusinessCentralApi() method.
    }
}