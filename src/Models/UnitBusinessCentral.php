<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Pionect\Daalder\Models\Unit\Unit;

/**
 * Class UnitBusinessCentral
 *
 * @package Daalder\BusinessCentral\Models
 */
class UnitBusinessCentral extends ReferenceModel
{
    protected $primaryKey = 'unit_id';
    protected $key = 'unit_id';
    protected $table = 'unit_business_central';
    protected $fillable = ['unit_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class);
    }
}
