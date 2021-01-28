<?php

namespace BusinessCentral\Controllers;

use BusinessCentral\Services\Order\SynchronizeOrder;
use Pionect\Backoffice\Http\Controllers\Order\OrderController as BaseOrderController;
use Pionect\Backoffice\Models\Order\Order;

class OrderController extends BaseOrderController
{
    /**
     * @param  Order  $order
     * @param  SynchronizeOrder  $synchronizeOrder
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function businessCentral(Order $order, SynchronizeOrder $synchronizeOrder)
    {
        $synchronizeOrder->sync($order);

        return redirect('order/'.$order->id);
    }

}
