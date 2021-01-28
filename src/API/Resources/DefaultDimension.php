<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\Product\Group
 */
class DefaultDimension extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'parentId' => (string) str_limit($this->id, 20),
            'dimensionId' => str_limit($this->name, 20),
            'dimensionValueId' => str_limit($this->name, 20),
            'postingValidation' => str_limit($this->name, 20),
        ];
    }
}
