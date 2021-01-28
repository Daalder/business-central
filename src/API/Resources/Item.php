<?php

namespace BusinessCentral\API\Resources;

use BusinessCentral\Models\SetBusinessCentral;
use BusinessCentral\Models\UnitBusinessCentral;
use BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 * @mixin \Pionect\Backoffice\Models\Product\Product
 */
class Item extends Resource
{

    /**
     * @var \BusinessCentral\Repositories\ReferenceRepository
     */
    private $referenceRepository;

    public function __construct($resource){
            parent::__construct($resource);

        $this->referenceRepository = app(ReferenceRepository::class);
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'baseUnitOfMeasureId' => $this->when($this->setUnit(), $this->setUnit()),
            'number'              => $this->sku,
            'displayName'         => str_limit($this->name, 97),
            'type'                => ($this->isShipping()) ? '2' : '0',
            'blocked'             => false,
            'gtin'                => $this->ean,
            'unitPrice'           => ((float) $this->specialprice) ? (float) $this->specialprice : (float) $this->price,
            'priceIncludesTax'    => true,
            'itemCategoryId'      => $this->when($this->setItemCategory(), $this->setItemCategory())
        ];

        return $data;
    }

    /**
     * @return string|null
     */
    private function setItemCategory()
    {
        $reference = $this->referenceRepository->getReference(
            new SetBusinessCentral(['productattributeset_id' => optional($this->productattributeset)->id])
        );

        return ($reference) ? $reference->business_central_id : null;
    }

    /**
     * @return string|null
     */
    private function setUnit(){

        if(!isset($this->saleUnit) || null === $this->saleUnit) {
            return null;
        }

        $reference = $this->referenceRepository->getReference(
            new UnitBusinessCentral(['unit_id' => $this->saleUnit->id])
        );

        return ($reference) ? $reference->business_central_id : null;
    }
}
