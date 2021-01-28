<?php

namespace BusinessCentral\Models;


use Pionect\Backoffice\Models\Unit\Unit;

/**
 * Class UnitBusinessCentral
 * @package BusinessCentral\Models
 */
class UnitBusinessCentral extends ReferenceModel
{

    protected $table = 'unit_business_central';
    protected $fillable = ['unit_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];
    public $primaryKey = 'unit_id';
    public $key = 'unit_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function unit()
    {
        return $this->hasOne(Unit::class);
    }
}