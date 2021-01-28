<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Daalder\BusinessCentral\Models\SetBusinessCentral;
use Daalder\BusinessCentral\Models\UnitBusinessCentral;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\Product\Product
 */
class Item extends Resource
{
    private \BusinessCentral\Repositories\ReferenceRepository $referenceRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->referenceRepository = app(ReferenceRepository::class);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'baseUnitOfMeasureId' => $this->when($this->setUnit(), $this->setUnit()),
            'number' => $this->sku,
            'displayName' => str_limit($this->name, 97),
            'type' => $this->isShipping() ? '2' : '0',
            'blocked' => false,
            'gtin' => $this->ean,
            'unitPrice' => (float) $this->specialprice ? (float) $this->specialprice : (float) $this->price,
            'priceIncludesTax' => true,
            'itemCategoryId' => $this->when($this->setItemCategory(), $this->setItemCategory()),
        ];
    }

    private function setItemCategory(): ?string
    {
        $reference = $this->referenceRepository->getReference(
            new SetBusinessCentral(['productattributeset_id' => optional($this->productattributeset)->id])
        );

        return $reference ? $reference->business_central_id : null;
    }

    private function setUnit(): ?string
    {
        if (! isset($this->saleUnit) || $this->saleUnit === null) {
            return null;
        }

        $reference = $this->referenceRepository->getReference(
            new UnitBusinessCentral(['unit_id' => $this->saleUnit->id])
        );

        return $reference ? $reference->business_central_id : null;
    }
}
