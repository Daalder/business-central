<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Carbon\Carbon;
use Pionect\Daalder\Models\BaseModel;

/**
 * Class ShippingMethod
 *
 * @package BusinessCentral\Models
 *
 * @property int id
 * @property int shipping_method_id
 * @property int business_central_id
 * @property Carbon created_at
 * @property Carbon updated_at
 */

class ShippingMethod extends BaseModel
{
    protected $incrementing = false;
    protected $table = 'shipping_methods_business_central';

    protected $fillable = [
        'shipping_method_id',
        'business_central_id',
        'id',
        'code',
        'displayName',
    ];

    public function shipping_method(): void
    {
        $this->belongsTo('shipping_methods', 'shipping_method_id', 'id');
    }
}
