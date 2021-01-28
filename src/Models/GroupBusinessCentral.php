<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\Product\Group;

/**
 * Class ProductBusinessCentral
 *
 * @package BusinessCentral\Models
 */
class GroupBusinessCentral extends ReferenceModel
{
    protected $table = 'group_business_central';
    protected $fillable = ['group_id', 'business_central_id'];
    protected $dates = ['created_at', 'updated_at'];
    public $key = 'group_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function set()
    {
        return $this->hasOne(Group::class);
    }
}
