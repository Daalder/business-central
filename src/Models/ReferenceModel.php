<?php

namespace BusinessCentral\Models;


use Pionect\Backoffice\Models\BaseModel;

/**
 * Class ReferenceModel
 *
 * @package BusinessCentral\Models
 * @property string business_central_id
 */
class ReferenceModel extends BaseModel
{
    public $key;

    public function getKey()
    {
        return $this->key;
    }
}
