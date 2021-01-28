<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Backoffice\Models\BaseModel;

/**
 * Class Shipment
 *
 * @package App\Models\Orders
 *
 * @property string shipment_reference
 * @property int line
 * @property string shipment_business_central_id
 * @property string order_reference
 * @property string order_business_central_id
 * @property string sku
 * @property int amount
 */
class ShipmentLine extends BaseModel
{
    protected $fillable = ['shipment_reference', 'line', 'shipment_business_central_id', 'order_reference', 'order_business_central_id', 'sku', 'amount'];
}
