<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Translators\Resource;

use App\Models\Products\Product;
use Daalder\BusinessCentral\API\Repositories\ItemRepository;
use Daalder\BusinessCentral\API\Resources\Daalder\TranslationProduct;
use Daalder\BusinessCentral\Jobs\Product\CreateProduct;
use Daalder\BusinessCentral\Jobs\Product\DeleteProduct;
use Daalder\BusinessCentral\Jobs\Product\UpdateProduct;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Daalder\BusinessCentral\Repositories\ProductRepository;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Daalder\BusinessCentral\Translators\Translator;
use Daalder\BusinessCentral\Validators\ProductBusinessCentralValidator;
use Exception;

class ProductTranslator extends Translator
{
    /**
     * Get Business Central repository class name.
     */
    public function businessCentralRepositoryName(): string
    {
        return ItemRepository::class;
    }

    /**
     * Get BackOffice repository class name.
     */
    public function backOfficeRepositoryName(): string
    {
        return ProductRepository::class;
    }

    /**
     * Make reference repository.
     *
     * @param $model
     */
    public function makeReferenceRepository($model = null): ReferenceRepository
    {
        if ($model !== null) {
            return new ReferenceRepository($model);
        }
        return resolve(ReferenceRepository::class);

    
    }

    /**
     * Create destination entity.
     *
     * @param array $data
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function create(array $data = [])
    {
        return $this->isFromBusinessCentral ? $this->createBusinessCentralBackOffice($data) : $this->createBackOfficeBusinessCentral($data);
    }

    /**
     * Update destination entity.
     *
     * @param array $data
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function update(array $data = [])
    {
        return $this->isFromBusinessCentral ? $this->updateBusinessCentralBackOffice($data) : $this->updateBackOfficeBusinessCentral($data);
    }

    /**
     * Delete destination entity.
     */
    public function delete(): bool
    {
        return $this->isFromBusinessCentral ? $this->deleteBusinessCentralBackOffice() : $this->deleteBackOfficeToBusinessCentral();
    }

    /**
     * Create destination entity without storing it in the database.
     *
     * @param array $data
     *
     * @return array|null
     *
     * @throws Exception
     */
    public function prepare(array $data = [], bool $debug = false): ?array
    {
        $destinationPayload = (new TranslationProduct($data))->resolve();

        if ($this->validatePayload(ProductBusinessCentralValidator::class, $destinationPayload, $debug) === false) {
            return null;
        }

        return $destinationPayload;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    protected function getReference()
    {
        $referenceParams = $this->isFromBusinessCentral
            ? ['business_central_id' => $this->translationBase['business_central_id']]
            : ['product_id' => $this->translationBase['id']];
        return $this->makeReferenceRepository()->getReference(new ProductBusinessCentral($referenceParams));
    }

    /**
     * Create BackOffice product from BusinessCentral data.
     * If reference exists, update one.
     *
     * @param array $data
     *
     * @throws Exception
     */
    protected function createBusinessCentralBackOffice(array $data = [], bool $debug = false): ?Product
    {
        if ($this->getReference($data)) {
            return $this->updateBusinessCentralBackOffice($data);
        }

        return $this->makeDestinationRepository(new Product())->store($this->prepare($data));
    }

    /**
     * Create BackOffice product from BusinessCentral data.
     *
     * @param array $data
     */
    protected function createBackOfficeBusinessCentral(array $data = []): bool
    {
        $reference = $this->getReference($data);
        if ($reference) {
            try {
                CreateProduct::dispatch($reference->product);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Update BackOffice product from BusinessCentral data.
     *
     * @param array $data
     *
     * @throws Exception
     */
    protected function updateBusinessCentralBackOffice(array $data = [], bool $debug = false): ?Product
    {
        if (($reference = $this->getReference()) === null) {
            return $this->updateBusinessCentralBackOffice($data);
        }

        $product = $this->sanitizeProductName($this->prepare($data, true), $reference->product);

        return $this->makeDestinationRepository($reference->product)->edit($reference->product, $product);
    }

    /**
     * @param array $payload
     *
     * @return array
     */
    protected function sanitizeProductName(array $payload, Product $product): array
    {
        $name = trim($payload['name']);
        $referenceName = trim($product->name);

        if (strpos($name, $referenceName) === 0 && strlen($name) <= strlen($referenceName)) {
            unset($payload['name']);
        }

        return $payload;
    }

    /**
     * Update BackOffice product from BusinessCentral data.
     *
     * @param array $data
     */
    protected function updateBackOfficeBusinessCentral(array $data = []): bool
    {
        $reference = $this->getReference();
        if ($reference) {
            try {
                UpdateProduct::dispatch($reference->product);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Delete BackOffice entity after BusinessCentral notification.
     */
    protected function deleteBusinessCentralBackOffice(): bool
    {
        $reference = ReferenceRepository::getReference(new ProductBusinessCentral(['business_central_id' => $this->translationBase['business_central_id']]));
        if ($reference) {
            return (new ProductRepository($reference->product))->destroy($reference->product->id) === 1;
        }

        return false;
    }

    /**
     * Delete BusinessCentral entity after BackOffice notification.
     *
     * @param array $data
     */
    protected function deleteBackOfficeToBusinessCentral(array $data = []): bool
    {
        $reference = $this->getReference();
        if ($reference) {
            try {
                DeleteProduct::dispatch($reference->product);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }
}
