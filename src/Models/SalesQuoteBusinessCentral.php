<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Backoffice\Models\Order\Order;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 *
 * @property string business_central_id
 * @property int order_id
 */
class SalesQuoteBusinessCentral extends ReferenceModel
{
    public $primaryKey = 'order_id';
    public $key = 'order_id';
    protected $table = 'sales_quote_business_central';
    protected $fillable = ['order_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function order(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Order::class);
    }
}
