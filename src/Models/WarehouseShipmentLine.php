<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\BaseModel;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 * @property string warehouseShipmentNo
 * @property int lineNo
 * @property string warehouseShipmentId
 * @property string sourceDocumentNo
 * @property string sourceDocumentId
 * @property string itemNo
 * @property int qty
 */

class WarehouseShipmentLine extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'warehouseShipmentNo',
        'lineNo',
        'warehouseShipmentId',
        'sourceDocumentNo',
        'sourceDocumentId',
        'itemNo',
        'qty'];

}
