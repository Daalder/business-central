<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Illuminate\Support\Facades\Storage;
use Pionect\Daalder\Models\Product\Product;

/**
 * Class PictureRepository
 *
 * @package Daalder\BusinessCentral\API\Repositories
 */
class PictureRepository extends RepositoryAbstract
{
    protected $objectName = 'picture';

    /**
     * @return false|\stdClass|null
     *
     * @throws \Exception
     */
    public function create(Product $product)
    {
        $reference = $this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]));
        $itemId = $reference->business_central_id;

        $featuredImage = $product->featuredImage()->first();

        if (! $featuredImage) {
            return false;
        }

        $filePath = 'temp/'.$featuredImage->hash;
        Storage::put($filePath, file_get_contents($featuredImage->src));

        $response = $this->client->patch(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$itemId.')/picture('.$itemId.')/content',
            null,
            [
                'contentType' => 'application/octet-stream',
                'file' => config('filesystems.disks.local.root').'/'.$filePath,
            ]
        );

        Storage::delete($filePath);

        return $response;
    }

    /**
     * @return false|\stdClass|null
     *
     * @throws \Exception
     */
    public function update(Product $product)
    {
        $reference = $this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]));
        $itemId = $reference->business_central_id;

        $featuredImage = $product->featuredImage()->first();

        if (! $featuredImage) {
            return false;
        }

        $filePath = 'temp/'.$featuredImage->hash;
        Storage::put($filePath, file_get_contents($featuredImage->src));

        $response = $this->client->patch(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$itemId.')/picture('.$itemId.')/content',
            null,
            [
                'contentType' => 'application/octet-stream',
                'file' => config('filesystems.disks.local.root').'/'.$filePath,
            ]
        );

        Storage::delete($filePath);

        return $response;
    }

    /**
     * @param $ref
     *
     * @return null
     */
    public function delete($ref)
    {
        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$ref.')/picture'
        );
    }
}
