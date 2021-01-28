<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\Order\Order;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 * @property string business_central_id
 * @property int order_id
 */
class OrderBusinessCentral extends ReferenceModel
{
    protected $table = 'order_business_central';
    protected $fillable = ['order_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'order_id';
    public $key = 'order_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
