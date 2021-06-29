<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use App\Models\Products\Product;
use Daalder\BusinessCentral\API\Resources\Daalder\TranslationProduct as ProductResource;
use Daalder\BusinessCentral\Contracts\BusinessCentralApiResource;
use Daalder\BusinessCentral\Jobs\Product\CreateProduct;
use Daalder\BusinessCentral\Jobs\Product\UpdateProduct;
use Exception;
use Pionect\Daalder\Http\Api\Requests\Product\StoreProductRequest;
use Pionect\Daalder\Models\Product\Product as BackOfficeProduct;
use Pionect\Daalder\Models\Product\Repositories\ProductRepository as BackofficeProductRepository;
use Pionect\Daalder\Models\ProductAttribute\Repositories\GroupRepository;

/**
 * Class ProductRepository
 *
 * // TODO (MK) Verify if CRUD after BC methods are necessary
 *
 * @package BusinessCentral\Repositories
 */
class ProductRepository extends BackofficeProductRepository implements BusinessCentralApiResource
{
    /**
     * ProductRepository constructor.
     */
    public function __construct(Product $product)
    {
        parent::__construct($product);
    }

    /**
     * @return mixed
     *
     * @throws Exception
     */
    public function getNotSyncedProductsOverview(GroupRepository $groupRepository)
    {
        //dd(get_class($this->model));
        $products = $this->model->doesntHave('businessCentral')->whereNotNull('productattributeset_id')
            ->where('productattributeset_id', '!=', '0')
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->with('productattributeset.productproperties', 'productproperties', 'images')
            ->paginate(25);

        $productDetailSize = config('image_sizes.product.default');

        /** @var Product $product */
        foreach ($products as $product) {

            // check properties
            $product->propertiesComplete = true;
            $product->imagesCorrect = true;

            foreach ($product->getPossibleProperties() as $property) {
                if ($property->required === 1) {
                    if (is_null($property->pivot) || empty($property->pivot->value)) {
                        $product->propertiesComplete = false;
                        break;
                    }
                }
            }

            // check images
            if (! $product->images || ! count($product->images)) {
                $product->imagesCorrect = false;
            } else {
                foreach ($product->images as $image) {
                    if ($image->metadata->width < $productDetailSize['width'] ||
                        $image->metadata->height < $productDetailSize['height']
                    ) {
                        $product->imagesCorrect = false;
                        break;
                    }
                }
            }

            $variations = $product->productvariations;
            $product->variations = '';
            foreach ($variations as $k => $variation) {
                if ($k === 0) {
                    $product->variations .= $variation->products()->count() . ' ';
                }
                if ($k > 0) {
                    $product->variations .= ', ';
                }
                $product->variations .= $variation->name;
            }
        }

        return $products;
    }

    /**
     * Delete resource after Business Central.
     *
     * @param array $items
     *
     * @return array
     */
    public function deleteAfterBusinessCentralApi(array $items = []): array
    {
        $result = [];
        foreach ($items as $item) {
            $reference = $this->referenceRepository->getReference(new ProductBusinessCentral(['business_central_id' => $item['id']]));
            if ($reference) {
                $result[$item['id']] = $this->smashToSmithereens([$reference->product->id]);
            }
        }

        return $result;
    }

    /**
     * Create resource from Business Central.
     *
     * @param array $items
     *
     * @return array
     *
     * @throws Exception
     */
    public function createFromBusinessCentralApi(array $items = []): array
    {
        $result = [];
        foreach ($items as $item) {
            // If reference exists then update
            $reference = $this->referenceRepository->getReference(new ProductBusinessCentral(['business_central_id' => $item['id']]));
            if ($reference) {
                $this->updateFromBusinessCentralApi([$item]);
            }

            $storeProductRequest = new StoreProductRequest();

            $resource = new Product($item);

            $product = $resource->resolve();

            $validator = Validator::make($product, $storeProductRequest->rules());

            if ($validator->fails()) {
                report(new Exception($validator->errors(), 422));

                $result[$item['id']] = $validator->errors();
            } else {
                $result[$item['id']] = $this->store($product);
            }
        }

        return $result;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    public function updateFromBusinessCentralApi(array $items = []): array
    {
        $result = [];

        $itemIds = array_pluck($items, 'id');
        $products = $this->getByBusinessCentralIds($itemIds);

        foreach ($products as $product) {
            /** @var Product $product */
            $itemKey = array_search($product->businessCentral->business_central_id, array_column($items, 'id'));
            $item = (array) $items[$itemKey];

            $resource = new ProductResource($item);
            $productData = $resource->resolve();

            // if product has specialprice then map to specialprice.
            if ($product->specialprice) {
                if (isset($productData['price'])) {
                    $productData['specialprice'] = $productData['price'];
                }
                if (isset($productData['price_excluding_vat'])) {
                    $productData['special_price_excluding_vat'] = $productData['price_excluding_vat'];
                }
                unset($productData['price']);
                unset($productData['price_excluding_vat']);
            }

            $this->edit($product, $productData);

            $result[$item['id']] = $product;
        }

        return $result;
    }

    /**
     * @param array $businessCentralIds
     *
     * @return mixed
     */
    public function getByBusinessCentralIds(array $businessCentralIds = [])
    {
        return $this->model->whereHas('businessCentral', static function ($query) use ($businessCentralIds): void {
            $query->whereIn('business_central_id', $businessCentralIds);
        })->with('businessCentral')->get();
    }

    public function edit($product, $input, bool $pushToBc = false): BackOfficeProduct
    {
        $product = parent::edit($product, $input);
        if ($pushToBc) {
            UpdateProduct::dispatch($product);
        }

        return $product;
    }

    /**
     * @param array $input
     */
    public function store(array $input, bool $pushToBc = false): BackOfficeProduct
    {
        $product = parent::store($input);
        if ($pushToBc) {
            CreateProduct::dispatch($product);
        }

        return $product;
    }
}
