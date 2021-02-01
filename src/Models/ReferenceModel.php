<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Models;

use Pionect\Backoffice\Models\BaseModel;

/**
 * Class ReferenceModel
 *
 * @package BusinessCentral\Models
 *
 * @property string business_central_id
 */
class ReferenceModel extends BaseModel
{
    protected $key;

    public function getKey()
    {
        return $this->key;
    }
}
