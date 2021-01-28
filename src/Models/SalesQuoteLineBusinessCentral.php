<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\Order\Orderrow;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 */
class SalesQuoteLineBusinessCentral extends ReferenceModel
{
    protected $table = 'sales_quote_line_business_central';
    protected $fillable = ['order_row_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'order_row_id';
    public $key = 'order_row_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function orderRow()
    {
        return $this->hasOne(OrderRow::class);
    }
}
