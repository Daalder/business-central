<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Customer
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\Customer\Customer
 */
class WarehouseShipment extends JsonResource
{
    /**
     * @param Request $request
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
