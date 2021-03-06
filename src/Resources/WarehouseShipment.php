<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Customer
 *
 * @package BusinessCentral\Resources
 *
 * @mixin \Pionect\Daalder\Models\Customer\Customer
 */
class WarehouseShipment extends JsonResource
{
    /**
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'number' => $this->resource->No,
            'order_number' => $this->resource->Source_No,
        ];
    }
}
