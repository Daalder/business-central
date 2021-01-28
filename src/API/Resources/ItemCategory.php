<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\ProductAttribute\Set
 */
class ItemCategory extends JsonResource
{

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'code' => (string) str_limit($this->id, 20),
            'displayName' => str_limit($this->name, 20),
        ];
    }
}
