<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\API\Resources\Item;
use Daalder\BusinessCentral\Commands\PullFromBusinessCentral;
use Daalder\BusinessCentral\Models\DefaultDimension;
use Daalder\BusinessCentral\Models\GroupBusinessCentral;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Pionect\Backoffice\Models\Product\Product;

/**
 * Class ItemRepository. Responsible for translating Daalder product into Business Central item (product).
 *
 * @package BusinessCentral\API\Repositories
 */
class ItemRepository extends RepositoryAbstract
{
    public string $objectName = 'item';

    /**
     * @param Product $product
     * @return \stdClass|null
     * @throws \Exception
     */
    public function create(Product $product): ?\stdClass
    {
        if (! $product->sku) {
            return null;
        }

        $item = new Item($product);

        if (strlen($product->sku) > 20) {
            throw new \Exception('Product sku too long (more then 20 chars)');
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

        if ($response->id !== '00000000-0000-0000-0000-000000000000') {
            $this->storeReference(new ProductBusinessCentral([
                'product_id' => $product->id,
                'business_central_id' => $response->id,
            ]));

            if ($product->group_id) {
                // Let's store group as defaultDimension
                $dimensionRepository = new DimensionRepository($this->client, $this->referenceRepository);
                $dimensionRepository->create(new DefaultDimension([
                    'parentId' => $response->id,
                    'dimensionId' => DimensionRepository::GROUP_DIMENSION,
                    'dimensionValueId' => $this->referenceRepository->getReference(new GroupBusinessCentral(['group_id' => $product->group_id]))->business_central_id,
                    'postingValidation' => $product->group->code,
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
     * @param Product $product
     * @return \stdClass|null
     * @throws \Exception
     */
    public function update(Product $product): ?\stdClass
    {
        /** @var ProductBusinessCentral $reference */
        $reference = $this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]));

        if ($reference) {
            $item = new Item($product);
            logger('BC product update: '.json_encode($item));
            return $this->client->patch(
                config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$reference->business_central_id.')', $item->resolve()
            );
        }
            // No reference then try to create.
        $this->create($product);

        return null;
    }

    /**
     * @param $ref
     * @return null
     */
    public function delete($ref)
    {
        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items('.$ref.')'
        );
    }

    /**
     * @param int|null $skipToken
     * @return \stdClass|null
     */
    public function get(?int $skipToken = null): ?\stdClass
    {
        return $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items' . ($skipToken ? '?$skiptoken='.$skipToken : '')
        );
    }

    /**
     * @param PullFromBusinessCentral $command
     * @param int $top
     * @param int $skip
     * @return \stdClass|null
     */
    public function pullReferences(PullFromBusinessCentral $command, int $top = 20000, int $skip = 0): ?\stdClass
    {
        $response = $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/items?$top='.$top.'&$skip='.$skip
        );

        foreach ($response->value as $item) {
            $product = Product::where('sku', $item->number)->withTrashed()->orderBy('id', 'desc')->first();
            if ($product) {
                if (! $this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]))) {
                    $this->storeReference(new ProductBusinessCentral([
                        'product_id' => $product->id,
                        'business_central_id' => $item->id,
                    ]));
                }
            } else {
                $command->error('Product not found: '.$item->number);
            }
        }

        return $response;
    }

    /**
     * @param Product $product
     */
    public function pullReferenceWithSku(Product $product): void
    {
        $response = $this->client->get(config('business-central.endpoint').'companies('.config('business-central.companyId').')/items?$filter=number eq \''.$product->sku.'\'');

        foreach ($response->value as $item) {
            $product = Product::where('sku', $item->number)->withTrashed()->orderBy('id', 'desc')->first();
            if ($product) {
                if (! $this->referenceRepository->getReference(new ProductBusinessCentral(['product_id' => $product->id]))) {
                    $this->storeReference(new ProductBusinessCentral([
                        'product_id' => $product->id,
                        'business_central_id' => $item->id,
                    ]));
                }
            } else {
                logger('Product not found: '.$product->id);
            }
        }
    }
}
