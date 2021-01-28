<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Illuminate\Support\Facades\Storage;
use Pionect\Backoffice\Models\Product\Product;

/**
 * Class Product
 *
 * @package App\BusinessCentral\API\Resources
 */
class PictureRepository extends RepositoryAbstract
{
    public $objectName = 'picture';

    /**
     * @return \stdClass|bool|null
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
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
     * @return \stdClass|bool|null
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
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
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function delete($ref)
    {
        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$ref.')/picture'
        );
    }
}
