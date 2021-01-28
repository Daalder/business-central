<?php

namespace BusinessCentral\Translators\Resource;

use App\Models\Products\Product;
use BusinessCentral\API\Repositories\ItemRepository;
use BusinessCentral\API\Resources\Daalder\TranslationProduct;
use BusinessCentral\Jobs\Product\CreateProduct;
use BusinessCentral\Jobs\Product\DeleteProduct;
use BusinessCentral\Jobs\Product\UpdateProduct;
use BusinessCentral\Models\ProductBusinessCentral;
use BusinessCentral\Repositories\ProductRepository;
use BusinessCentral\Repositories\ReferenceRepository;
use BusinessCentral\Translators\Translator;
use BusinessCentral\Validators\ProductBusinessCentralValidator;
use Exception;

class ProductTranslator extends Translator
{
    /**
     * Get Business Central repository class name.
     *
     * @return string
     */
    public function businessCentralRepositoryName(): string
    {
        return ItemRepository::class;
    }

    /**
     * Get BackOffice repository class name.
     *
     * @return string
     */
    public function backOfficeRepositoryName(): string
    {
        return ProductRepository::class;
    }

    /**
     * Make reference repository.
     *
     * @param $model
     * @return ReferenceRepository
     */
    public function makeReferenceRepository($model = null)
    {
        if(null !== $model) {
            return new ReferenceRepository($model);
        } else {
            return resolve(ReferenceRepository::class);
        }
    }

    /**
     * @param array $data
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
     * Create destination entity.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function create(array $data = [])
    {
        return $this->isFromBusinessCentral ? $this->createBusinessCentralBackOffice($data) : $this->createBackOfficeBusinessCentral($data);
    }

    /**
     * Create BackOffice product from BusinessCentral data.
     * If reference exists, update one.
     *
     * @param array $data
     * @param bool $debug
     * @throws Exception
     * @return Product|null
     */
    protected function createBusinessCentralBackOffice(array $data = [], bool $debug = false): ?Product
    {
        if($this->getReference($data)) {
            return $this->updateBusinessCentralBackOffice($data);
        }

        return $this->makeDestinationRepository(new Product())->store($this->prepare($data));
    }

    /**
     * Create BackOffice product from BusinessCentral data.
     *
     * @param array $data
     * @return bool
     */
    protected function createBackOfficeBusinessCentral(array $data = []) {
        $reference = $this->getReference($data);
        if($reference) {
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
     * Update destination entity.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function update(array $data = [])
    {
        return $this->isFromBusinessCentral ? $this->updateBusinessCentralBackOffice($data) : $this->updateBackOfficeBusinessCentral($data);
    }

    /**
     * Update BackOffice product from BusinessCentral data.
     *
     * @param array $data
     * @param bool $debug
     * @return Product|null
     * @throws Exception
     */
    protected function updateBusinessCentralBackOffice(array $data = [], bool $debug = false)
    {
        if(null === ($reference = $this->getReference())) {
            return $this->updateBusinessCentralBackOffice($data);
        }

        $product = $this->sanitizeProductName($this->prepare($data, true), $reference->product);

        return $this->makeDestinationRepository($reference->product)->edit($reference->product, $product);
    }

    /**
     * @param array $payload
     * @param Product $product
     * @return array
     */
    protected function sanitizeProductName(array $payload, Product $product): array
    {
        $name = trim($payload['name']);
        $referenceName = trim($product->name);

        if(0 === strpos($name, $referenceName) && strlen($name) <= strlen($referenceName)) {
            unset($payload['name']);
        }

        return $payload;
    }

    /**
     * Update BackOffice product from BusinessCentral data.
     *
     * @param array $data
     * @return bool
     */
    protected function updateBackOfficeBusinessCentral(array $data = [])
    {
        $reference = $this->getReference();
        if($reference) {
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
     * Delete destination entity.
     *
     * @return bool
     */
    public function delete(): bool
    {
        return $this->isFromBusinessCentral ? $this->deleteBusinessCentralBackOffice() : $this->deleteBackOfficeToBusinessCentral();
    }

    /**
     * Delete BackOffice entity after BusinessCentral notification.
     */
    protected function deleteBusinessCentralBackOffice(): bool
    {
        $reference = ReferenceRepository::getReference(new ProductBusinessCentral(['business_central_id' => $this->translationBase['business_central_id']]));
        if($reference) {
            return 1 === (new ProductRepository($reference->product))->destroy($reference->product->id);
        }

        return false;
    }

    /**
     * Delete BusinessCentral entity after BackOffice notification.
     * @param array $data
     * @return bool
     */
    protected function deleteBackOfficeToBusinessCentral(array $data = []): bool
    {
        $reference = $this->getReference();
        if($reference) {
            try {
                DeleteProduct::dispatch($reference->product);
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Create destination entity without storing it in the database.
     *
     * @param array $data
     * @param bool $debug
     * @return array|null
     * @throws Exception
     */
    public function prepare(array $data = [], bool $debug = false): ?array
    {
        $destinationPayload = (new TranslationProduct($data))->resolve();

        if(false === $this->validatePayload(ProductBusinessCentralValidator::class, $destinationPayload, $debug)) {
            return null;
        }

        return $destinationPayload;
    }
}