<?php

namespace BusinessCentral\Models;

use Pionect\Backoffice\Models\BaseModel;

/**
 * Class DefaultDimension
 *
 * @package BusinessCentral\Models
 * @property string parentId
 * @Property string dimensionId
 * @property string dimensionValueId
 * @property string postingValidation
 */
class DefaultDimension extends BaseModel
{

    protected $fillable = [
        'parentId',
        'dimensionId',
        'dimensionValueId',
        'postingValidation'
    ];

}
