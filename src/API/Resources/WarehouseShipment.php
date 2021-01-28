<?php

namespace BusinessCentral\API\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Customer
 *
 * @package BusinessCentral\API\Resources
 * @mixin \Pionect\Backoffice\Models\Customer\Customer
 */
class WarehouseShipment extends Resource
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'number'       => $this->resource->No,
            'order_number' => $this->resource->Source_No,
        ];
    }
}
