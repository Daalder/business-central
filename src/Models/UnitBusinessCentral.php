<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Backoffice\Models\Unit\Unit;

/**
 * Class UnitBusinessCentral
 *
 * @package BusinessCentral\Models
 */
class UnitBusinessCentral extends ReferenceModel
{
    public $primaryKey = 'unit_id';
    public $key = 'unit_id';
    protected $table = 'unit_business_central';
    protected $fillable = ['unit_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function unit(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Unit::class);
    }
}
