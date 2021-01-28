<?php

namespace BusinessCentral\API\Resources\Daalder;


use Illuminate\Http\Resources\Json\Resource;
use Pionect\Backoffice\Models\Order\State;

/**
 * Class Order
 *
 * @package BusinessCentral\API\Resources\Daalder
 */
class Order extends Resource
{

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'state_id' => State::OPEN,
            'date'     => $request->get('orderDate'),
            'total'    => $request->get('totalAmountExcludingTax')
        ];
    }
}
