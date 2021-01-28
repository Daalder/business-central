<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class salesOrderLine
 *
 * @package Daalder\BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\Order\Orderrow
 */
class SalesQuoteLine extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'lineType' => 'Item',
            'quantity' => $this->amount,
            'unitPrice' => (float) $this->price,
            //'currencyCode' => "EURO",
            //'paymentTerms' => "30 DAGEN",
        ];
    }
}
