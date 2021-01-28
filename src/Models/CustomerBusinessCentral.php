<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\Customer\Customer;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 * @property Customer customer
 */
class CustomerBusinessCentral extends ReferenceModel
{
    protected $table = 'customer_business_central';
    public $fillable = ['customer_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'customer_id';
    public $key = 'customer_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne(Customer::class);
    }
}
