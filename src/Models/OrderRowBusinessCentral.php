<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Backoffice\Models\Order\Orderrow;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 */
class OrderRowBusinessCentral extends ReferenceModel
{
    public $key = 'order_row_id';
    protected $table = 'order_row_business_central';
    protected $fillable = ['order_row_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function orderRow(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(OrderRow::class);
    }
}
