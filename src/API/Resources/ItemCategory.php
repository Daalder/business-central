<?php

namespace BusinessCentral\API\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 * @mixin \Pionect\Backoffice\Models\ProductAttribute\Set
 */
class ItemCategory extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'code'        => (string) str_limit($this->id, 20),
            'displayName' => str_limit($this->name, 20)
        ];
    }
}
