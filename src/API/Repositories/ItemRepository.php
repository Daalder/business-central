<?php

namespace BusinessCentral\API\Repositories;

use BusinessCentral\API\Resources\Item;
use BusinessCentral\Commands\PullFromBusinessCentral;
use BusinessCentral\Models\DefaultDimension;
use BusinessCentral\Models\GroupBusinessCentral;
use BusinessCentral\Models\ProductBusinessCentral;
use Pionect\Backoffice\Models\Product\Product;

/**
 * Class ItemRepository. Responsible for translating Daalder product into Business Central item (product).
 * @package BusinessCentral\API\Repositories
 */
class ItemRepository extends RepositoryAbstract
{
    /**
     * @var string
     */
    public $objectName = 'item';

    /**
     * @param  \Pionect\Backoffice\Models\Product\Product  $product
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function create(Product $product)
    {
        if(!$product->sku)
            return null;

        $item = new Item($product);

        if (strlen($product->sku) > 20) {
            throw new \Exception("Product sku too long (more then 20 chars)");
        }
        // If we have a reference then try to update.
        if ($this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]))) {
            return $this->update($product);
        }

        logger('BC product create: '.json_encode($item));

        try {
            $response = $this->client->post(
                config('business-central.endpoint').'companies('.config('business-central.companyId').')/items', $item->resolve()
            );
        } catch (\Exception $exception) {
            // When we get an error check if there's a product with this sku in BC
            $this->client->item()->pullReferenceWithSku($product);
            logger($exception->getMessage());
            return null;
        }

        if ($response->id != '00000000-0000-0000-0000-000000000000') {
            $this->storeReference(new ProductBusinessCentral([
                'product_id'          => $product->id,
                'business_central_id' => $response->id
            ]));

            if($product->group_id) {
                // Let's store group as defaultDimension
                $dimensionRepository = new DimensionRepository($this->client, $this->referenceRepository);
                $dimensionRepository->create(new DefaultDimension([
                    'parentId' => $response->id,
                    'dimensionId' => DimensionRepository::GROUP_DIMENSION,
                    'dimensionValueId' => $this->referenceRepository->getReference(new GroupBusinessCentral(['group_id' => $product->group_id]))->business_central_id,
                    'postingValidation' => $product->group->code
                ]));
            }
            //$pictureRepository = new PictureRepository($this->client, $this->referenceRepository);
            //$pictureRepository->create($product);
            
        } else {
            logger('BC: Failed to create product: '.$product->id);
        }

        return $response;
    }

    /**
     * @param  \Pionect\Backoffice\Models\Product\Product  $product
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function update(Product $product)
    {
        /** @var ProductBusinessCentral $reference */
        $reference = $this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]));

        if ($reference) {
            $item = new Item($product);
            logger('BC product update: '.json_encode($item));
            $response = $this->client->patch(
                config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$reference->business_central_id.')', $item->resolve()
            );

            //$pictureRepository = new PictureRepository($this->client, $this->referenceRepository);
            //$pictureRepository->create($product);

            return $response;
        } else {
            // No reference then try to create.
            $this->create($product);
        }

        return null;
    }

    /**
     * @param $ref
     * @return null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function delete($ref)
    {
        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$ref.')'
        );
    }

    /**
     * @param  int  $skipToken
     * @return \stdClass|null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function get($skipToken = null)
    {
        $response = $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items' . ($skipToken ? '?$skiptoken='.$skipToken : '')
        );

        return $response;
    }

    /**
     * @param  \BusinessCentral\Commands\PullFromBusinessCentral  $command
     * @param  int  $top
     * @param  int  $skip
     * @return \stdClass|null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function pullReferences(PullFromBusinessCentral $command, $top = 20000, $skip = 0)
    {

        $response = $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items?$top='.$top.'&$skip='.$skip
        );

        foreach ($response->value as $item) {
            $product = Product::where('sku', $item->number)->withTrashed()->orderBy('id', 'desc')->first();
            if ($product) {
                if (!$this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]))) {
                    $this->storeReference(new ProductBusinessCentral([
                        'product_id'          => $product->id,
                        'business_central_id' => $item->id
                    ]));
                }
            } else {
                $command->error('Product not found: '.$item->number);
            }
        }

        return $response;
    }

    /**
     * @param  \Pionect\Backoffice\Models\Product\Product  $product
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function pullReferenceWithSku(Product $product)
    {
        $response = $this->client->get(config('business-central.endpoint').'companies('.config('business-central.companyId').')/items?$filter=number eq \''.$product->sku.'\'');

        foreach ($response->value as $item) {
            $product = Product::where('sku', $item->number)->withTrashed()->orderBy('id', 'desc')->first();
            if ($product) {
                if (!$this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]))) {
                    $this->storeReference(new ProductBusinessCentral([
                        'product_id'          => $product->id,
                        'business_central_id' => $item->id
                    ]));
                }
            } else {
                logger('Product not found: '.$product->id);
            }
        }
    }
}
