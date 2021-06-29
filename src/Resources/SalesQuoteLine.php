<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class salesOrderLine
 *
 * @package Daalder\BusinessCentral\Resources
 *
 * @mixin \Pionect\Daalder\Models\Order\Orderrow
 */
class SalesQuoteLine extends JsonResource
{
    /**
     * @return array
     */
    public function toArray(Request $request): array
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
