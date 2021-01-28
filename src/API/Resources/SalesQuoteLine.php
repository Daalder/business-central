<?php

namespace BusinessCentral\API\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class salesOrderLine
 *
 * @package BusinessCentral\API\Resources
 * @mixin \Pionect\Backoffice\Models\Order\Orderrow
 */
class SalesQuoteLine extends Resource
{

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'lineType'  => 'Item',
            'quantity'  => $this->amount,
            'unitPrice' => (float) $this->price,
            //'currencyCode' => "EURO",
            //'paymentTerms' => "30 DAGEN",
        ];

    }
}
