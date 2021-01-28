<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Customer
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\Customer\Customer
 */
class WarehouseShipment extends Resource
{
    /**
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'number' => $this->resource->No,
            'order_number' => $this->resource->Source_No,
        ];
    }
}
