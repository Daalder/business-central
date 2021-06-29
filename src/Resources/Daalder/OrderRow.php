<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Resources\Daalder;

use Illuminate\Http\Resources\Json\JsonResource;
use Pionect\Backoffice\Models\Order\State;

/**
 * Class Order
 *
 * @package BusinessCentral\Resources\Daalder
 */
class OrderRow extends JsonResource
{
    /**
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'state_id' => State::OPEN,
            'date' => $request->get('orderDate'),
            'total' => $request->get('totalAmountExcludingTax'),
        ];
    }
}
