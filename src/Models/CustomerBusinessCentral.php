<?php

namespace Daalder\BusinessCentral\Models;

use Pionect\Daalder\Models\Customer\Customer;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 *
 * @property Customer customer
 */
class CustomerBusinessCentral extends ReferenceModel
{
    protected $fillable = ['customer_id', 'business_central_id'];
    protected $primaryKey = 'customer_id';
    protected $key = 'customer_id';
    protected $table = 'customer_business_central';
    protected $dates = ['created_at', 'updated_at'];

    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Customer::class);
    }
}
