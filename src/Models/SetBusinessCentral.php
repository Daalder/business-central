<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\ProductAttribute\Set;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 */
class SetBusinessCentral extends ReferenceModel
{
    protected $table = 'productattributeset_business_central';
    protected $fillable = ['productattributeset_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'productattributeset_id';
    public $key = 'productattributeset_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function set()
    {
        return $this->hasOne(Set::class);
    }
}
