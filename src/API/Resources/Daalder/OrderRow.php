<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources\Daalder;

use Illuminate\Http\Resources\Json\Resource;
use Pionect\Backoffice\Models\Order\State;

/**
 * Class Order
 *
 * @package BusinessCentral\API\Resources\Daalder
 */
class OrderRow extends Resource
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
