<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Controllers;

use Daalder\BusinessCentral\API\Repositories\CustomerRepository as CustomerBusinessCentralRepository;
use Daalder\BusinessCentral\API\Resources\Daalder\Customer;
use Daalder\BusinessCentral\API\Resources\Daalder\TranslationProduct;
use Daalder\BusinessCentral\Models\CustomerBusinessCentral;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Daalder\BusinessCentral\Validators\ProductBusinessCentralValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Pionect\Backoffice\Http\Api\Requests\Customer\RegisterCustomerRequest;
use Pionect\Backoffice\Http\Api\Requests\Product\StoreProductRequest;
use Pionect\Backoffice\Http\Controllers\BaseController;
use Pionect\Backoffice\Http\Requests\Customer\UpdateCustomer;
use Pionect\Backoffice\Models\Customer\Repositories\CustomerRepository;
use Pionect\Backoffice\Models\Product\Repositories\ProductRepository;

/**
 * Class NotifyController
 *
 * @package BusinessCentral\Controllers
 */
class NotifyController extends BaseController
{
    /**
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     *
     * @return \Illuminate\Http\JsonResponse|\Pionect\Backoffice\Models\Product\Product
     *
     * @throws \Exception
     */
    public function createItem(Request $request, ProductRepository $repository, ReferenceRepository $referenceRepository)
    {
        /**
         * AFTER TRANSLATOR ARE READY:
         *
         * $product = TranslatorFactory::fromBusinessCentral($request->all())->create();
         */
        // If reference exists then update
        $reference = $referenceRepository->getReference(new ProductBusinessCentral(['business_central_id' => $request->get('id')]));
        if ($reference) {
            $this->updateItem($request, $repository, $referenceRepository);
        }

        $rules = (new StoreProductRequest())->rules();
//        unset($rules['productattributeset_id']);

        $resource = new TranslationProduct($request->all());

        $product = $resource->resolve();

        $validator = Validator::make($product, $rules);

        if ($validator->fails()) {
            report(new \Exception($validator->errors()->toJson(), 422));

            return response()->json($validator->errors()->all(), 422);
        }

        return response()->json($repository->store($product)->toJson());
    }

    /**
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     *
     * @return \Illuminate\Http\JsonResponse|\Pionect\Backoffice\Models\Product\Product
     *
     * @throws \Exception
     */
    public function updateItem(Request $request, ProductRepository $repository, ReferenceRepository $referenceRepository)
    {
        $resource = new TranslationProduct($request->all());
        $product = $resource->resolve();

        /**
         * @var ProductBusinessCentral $reference
         * Get reference.
         */
        $reference = $referenceRepository->getReference(new ProductBusinessCentral(['business_central_id' => $request->get('id')]));
        if ($reference) {

            // Unset name because char limit Business Central
            // Updated to work only when names are the sames, but only truncated within the BusinessCentral.
            // If a product name changes in the BusinessCentral, a new name will be passed
            $productName = trim($product['name']);
            $referenceName = trim($reference->product->name);
            if (strpos($productName, $referenceName) === 0 && strlen($productName) <= strlen($referenceName)) {
                unset($product['name']);
            }

            $validator = ProductBusinessCentralValidator::make($request->all());
            if ($validator->fails()) {
                report(new \Exception($validator->errors()->toJson(), 422));

                return response()->json($validator->errors()->all(), 422);
            }

            return response()->json($repository->edit($reference->product, $product)->toJson());
        }
        // No reference then try to create
        return $this->createItem($request, $repository, $referenceRepository);
    }

    /**
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     *
     * @return \Illuminate\Http\JsonResponse|\Pionect\Backoffice\Models\Product\Product
     */
    public function deleteItem(Request $request, ProductRepository $repository, ReferenceRepository $referenceRepository)
    {
        /**
         * @var ProductBusinessCentral $reference
         * Get reference.
         */
        $reference = $referenceRepository->getReference(new ProductBusinessCentral(['business_central_id' => $request->get('id')]));
        if ($reference) {
            return response()->json(json_encode($repository->smashToSmithereens([$reference->product->id])));
        }

        return response()->json('', 401);
    }

    /**
     * @param  \BusinessCentral\API\Repositories\CustomerRepository  $customerBusinessCentralRepository
     *
     * @return \Illuminate\Http\JsonResponse|\Pionect\Backoffice\Models\Customer\Customer
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function createCustomer(Request $request, CustomerRepository $repository, CustomerBusinessCentralRepository $customerBusinessCentralRepository)
    {
        // Get customer by id
        $payload = $customerBusinessCentralRepository->get($request->get('id'));
        $storeCustomerRequest = new RegisterCustomerRequest();

        $resource = new Customer($payload);

        $customer = $resource->resolve();

        $validator = Validator::make($customer, $storeCustomerRequest->rules());

        if ($validator->fails()) {
            report(new \Exception($validator->errors()->toJson(), 422));

            return response()->json($validator->errors()->all(), 422);
        }

        return response()->json($repository->store($customer)->toJson());
    }

    /**
     * @param  \BusinessCentral\API\Repositories\CustomerRepository  $customerBusinessCentralRepository
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function updateCustomer(Request $request, CustomerRepository $repository, CustomerBusinessCentralRepository $customerBusinessCentralRepository, ReferenceRepository $referenceRepository): \Illuminate\Http\JsonResponse
    {
        // Get customer by id
        $payload = $customerBusinessCentralRepository->get($request->get('id'));

        $updateCustomerRequest = new UpdateCustomer();
        $resource = new Customer($payload);
        $customer = $resource->resolve();

        /**
         * @var CustomerBusinessCentral $reference
         * Get reference.
         */
        $reference = $referenceRepository->getReference(new CustomerBusinessCentral(['business_central_id' => $request->get('id')]));
        if ($reference) {
            $validator = Validator::make($customer, $updateCustomerRequest->rules());
            if ($validator->fails()) {
                report(new \Exception($validator->errors()->toJson(), 422));

                return response()->json($validator->errors()->all(), 422);
            }

            return response()->json($repository->edit($reference->customer, $customer)->toJson());
        }
        // No reference then try to create
        return $this->createCustomer($request, $repository, $customerBusinessCentralRepository);
    }

    public function createSalesOrder(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json('', 501);
    }
}
