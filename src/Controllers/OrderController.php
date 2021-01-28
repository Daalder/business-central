<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Controllers;

use Daalder\BusinessCentral\Services\Order\SynchronizeOrder;
use Pionect\Backoffice\Http\Controllers\Order\OrderController as BaseOrderController;
use Pionect\Backoffice\Models\Order\Order;

class OrderController extends BaseOrderController
{
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function businessCentral(Order $order, SynchronizeOrder $synchronizeOrder)
    {
        $synchronizeOrder->sync($order);

        return redirect('order/'.$order->id);
    }
}
