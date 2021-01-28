<?php

namespace BusinessCentral\API\Resources\Daalder;

use Illuminate\Http\Resources\Json\Resource;
use Pionect\Backoffice\Models\Product\Type;
use Pionect\Backoffice\Models\ProductAttribute\Set;


/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class TranslationProduct extends Resource
{
    /**
     * @var
     */
    protected $data;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!isset($this->data) || null === $this->data || !is_array($this->data)) {
            $this->data = $this->resource;
        }

        $this->setPrice();
        // disable productAttributeSet because it isn't pushed correctly
//        $this->setProductAttributeSet();
        $this->setInventory();

        return array_merge($this->data, [
            'sku'             => array_get($this->resource, 'number'),
            // because of char cap we cannot pull displayName. Will be fixed in BC April release by MS.
            // uncommented because name sanitation will be performed elsewhere
            'name' => array_get($this->resource, 'displayName'),
            'ean'             => array_get($this->resource, 'gtin'),
            'cost_price'      => array_get($this->resource, 'unitCost'),
            'productattributeset_id' => $this->setProductAttributeSet(),
            'product_type_id' => Type::SIMPLE
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

    /**
     *
     */
    private function setInventory()
    {
        $inventory = array_get($this->resource, 'inventory');

        if ($inventory !== null) {
            $this->data['in_stock'] = $inventory;
        }
    }
}
