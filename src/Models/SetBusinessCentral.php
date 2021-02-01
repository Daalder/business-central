<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Backoffice\Models\ProductAttribute\Set;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 */
class SetBusinessCentral extends ReferenceModel
{
    protected $primaryKey = 'productattributeset_id';
    protected $key = 'productattributeset_id';
    protected $table = 'productattributeset_business_central';
    protected $fillable = ['productattributeset_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];

    public function set(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Set::class);
    }
}
