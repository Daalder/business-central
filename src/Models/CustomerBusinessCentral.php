<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Backoffice\Models\Customer\Customer;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 *
 * @property Customer customer
 */
class CustomerBusinessCentral extends ReferenceModel
{
    public $fillable = ['customer_id', 'business_central_id'];
    public $primaryKey = 'customer_id';
    public $key = 'customer_id';
    protected $table = 'customer_business_central';
    protected $dates = ['created_at', 'updated_at'];

    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Customer::class);
    }
}
