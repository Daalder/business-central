<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use App\Models\Orders\Order;
use Daalder\BusinessCentral\Contracts\BusinessCentralApiResource;
use Pionect\Backoffice\Models\Order\Repositories\OrderRepository as BackOfficeOrderRepository;

class OrderRepository extends BackOfficeOrderRepository implements BusinessCentralApiResource
{
    /**
     * OrderRepository constructor.
     */
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }

    /**
     * Update resource from Business Central.
     *
     * @param array $items
     */
    public function updateFromBusinessCentralApi(array $items = []): bool
    {
        // TODO: Implement updateFromBusinessCentralApi() method.
    }

    /**
     * Delete resource after Business Central.
     *
     * @param array $items
     */
    public function deleteAfterBusinessCentralApi(array $items = []): bool
    {
        // TODO: Implement deleteAfterBusinessCentralApi() method.
    }

    /**
     * Create resource from Business Central.
     *
     * @param array $items
     */
    public function createFromBusinessCentralApi(array $items = []): bool
    {
        // TODO: Implement createFromBusinessCentralApi() method.
    }
}
