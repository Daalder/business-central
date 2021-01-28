<?php

namespace BusinessCentral\Models;

use App\Models\Shipping\ShippingProvider;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Pionect\Backoffice\Models\Address\Address;
use Pionect\Backoffice\Models\BaseModel;
use Pionect\Backoffice\Models\Order\Order;

/**
 * Class Shipment
 * @package App\Models\Orders
 * @property string reference
 * @property string business_central_id
 * @property string track_and_trace
 * @property string phone
 * @property string email
 * @property integer number_of_colli
 * @property string trip_number
 * @property string work_description
 * @property string customer_name
 * @property string shipment_method_code
 * @property string load_status
 * @property string salesperson_code
 * @property integer week_number
 * @property boolean shipped
 * @property boolean delivered
 * @property string estimated_delivery_date
 * @property integer order_id
 * @property integer shipping_provider_id
 * @property integer shipping_address_id
 * @property Order order
 * @property Address shippingAddress
 * @property ShippingProvider provider
 * @property string shipment_date
 * @property string pakbon_printed_at
 * @property string picking_list_printed_at
 * @property string last_email_sent_time_cust
 * @property string last_email_sent_time_complete
 * @property string last_email_sent_time
 * @property string sent_as_email
 * @property string sent_as_email_complete
 * @property string external_document_no
 */
class Shipment extends BaseModel
{

    protected $fillable = [
        'business_central_id',
        'sort_order',
        'reference',
        'track_and_trace',
        'phone',
        'email',
        'number_of_colli',
        'trip_number',
        'week_number',
        'shipped',
        'delivered',
        'order_id',
        'work_description',
        'load_status',
        'customer_name',
        'shipment_method_code',
        'salesperson_code',
        'planned_delivery_date',
        'sent_as_email_cust',
        'shipment_date',
        'pakbon_printed_at',
        'picking_list_printed_at',
        'last_email_sent_time_cust',
        'last_email_sent_time_complete',
        'last_email_sent_time',
        'sent_as_email',
        'sent_as_email_complete',
        'external_document_no'
    ];


    protected $dates = [
        'planned_delivery_date',
        'shipment_date',
        'pakbon_printed_at',
        'picking_list_printed_at',
        'last_email_sent_time_cust',
        'last_email_sent_time_complete',
        'last_email_sent_time',
    ];

    /**
     * @return BelongsTo
     */
    public function shippingAddress() : BelongsTo
    {
        return $this->belongsTo(Address::class,'shipping_address_id');
    }

    /**
     * @return BelongsTo
     */
    public function provider() : BelongsTo
    {
        return $this->belongsTo(ShippingProvider::class, 'shipping_provider_id');
    }

    /**
     * @return BelongsTo
     */
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
