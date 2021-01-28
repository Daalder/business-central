<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\BaseModel;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 * @property string id
 * @property string no
 * @property string salesOrderId
 * @property int sortOrder
 * @property string sortingMethod
 * @property string salespersonCode
 * @property string customerName
 * @property string shippingAddress
 * @property string shippingAddressPostalCode
 * @property string shippingAddressPostalCity
 * @property string shippingAddressPostalCountry
 * @property string customerEmail
 * @property string customerPhone
 * @property string workDescription
 * @property string loadStatus
 * @property string shippingAgentCode
 * @property string shippingAgentServiceCode
 * @property string shipmentMethodCode
 * @property string tripnumber
 * @property string weeknumber
 * @property string noOfColli
 * @property string PlannedDeliveryDate
 * @property string SentasEmailCust
 * @property string ShipmentDate
 * @property string PakbonPrintedAt
 * @property string PickingListPrintedAt
 * @property string LastEmailSentTimeCust
 * @property string LastEmailSentTimeComplete
 * @property string LastEmailSentTime
 * @property string SentAsEmail
 * @property string SentAsEmailComplete
 * @property string ExternalDocumentNo
 */

class WarehouseShipment extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'no',
        'salesOrderId',
        'sortOrder',
        'sortingMethod',
        'salespersonCode',
        'customerName',
        'shippingAddress',
        'shippingAddressPostalCode',
        'shippingAddressPostalCity',
        'shippingAddressPostalCountry',
        'customerEmail',
        'customerPhone',
        'workDescription',
        'loadStatus',
        'shippingAgentCode',
        'shippingAgentServiceCode',
        'shipmentMethodCode',
        'tripnumber',
        'weeknumber',
        'noOfColli',
        'PlannedDeliveryDate',
        'SentasEmailCust',
        'ShipmentDate',
        'PakbonPrintedAt',
        'PickingListPrintedAt',
        'LastEmailSentTimeCust',
        'LastEmailSentTimeComplete',
        'LastEmailSentTime',
        'SentAsEmail',
        'SentAsEmailComplete',
        'ExternalDocumentNo'
        ];

}
