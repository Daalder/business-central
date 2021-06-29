<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Resources\Daalder;

use Illuminate\Http\Resources\Json\JsonResource;
use Pionect\Daalder\Models\Product\Type;
use Pionect\Daalder\Models\ProductAttribute\Set;

/**
 * Class Product
 *
 * @package BusinessCentral\Resources
 */
class TranslationProduct extends JsonResource
{
    /**
     * @var
     */
    protected $data;

    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        if (! isset($this->data) || $this->data === null || ! is_array($this->data)) {
            $this->data = $this->resource;
        }

        $this->setPrice();
        // disable productAttributeSet because it isn't pushed correctly
//        $this->setProductAttributeSet();
        $this->setInventory();

        return array_merge($this->data, [
            'sku' => array_get($this->resource, 'number'),
            // because of char cap we cannot pull displayName. Will be fixed in BC April release by MS.
            // uncommented because name sanitation will be performed elsewhere
            'name' => array_get($this->resource, 'displayName'),
            'ean' => array_get($this->resource, 'gtin'),
            'cost_price' => array_get($this->resource, 'unitCost'),
            'productattributeset_id' => $this->setProductAttributeSet(),
            'product_type_id' => Type::SIMPLE,
        ]);
    }

    /**
     * @return mixed
     */
    private function setPrice()
    {
        if (array_get($this->resource, 'priceIncludesTax')) {
            $this->data['price'] = array_get($this->resource, 'unitPrice');
        } else {
            $this->data['price_excluding_vat'] = array_get($this->resource, 'unitPrice');
        }
    }

    /**
     * @return mixed
     */
    private function setProductAttributeSet()
    {
        $set = Set::find((int) array_get($this->resource, 'itemCategoryCode'));

        if ($set) {
            return $set->id;
        }

        return null;
    }

    private function setInventory(): void
    {
        $inventory = array_get($this->resource, 'inventory');

        if ($inventory !== null) {
            $this->data['in_stock'] = $inventory;
        }
    }
}
