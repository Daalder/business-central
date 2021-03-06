<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Product
 *
 * @package BusinessCentral\Resources
 *
 * @mixin \Pionect\Daalder\Models\Product\Group
 */
class DefaultDimension extends JsonResource
{
    /**
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'parentId' => (string) str_limit($this->id, 20),
            'dimensionId' => str_limit($this->name, 20),
            'dimensionValueId' => str_limit($this->name, 20),
            'postingValidation' => str_limit($this->name, 20),
        ];
    }
}
