<?php

namespace BusinessCentral\API\Repositories;


use BusinessCentral\Models\ShippingMethod as BusinessCentralShippingMethod;
use Pionect\Backoffice\Models\Shipping\ShippingMethod as DaalderShippingMethod;

/**
 * Class ShippingMethodsRepository
 *
 * @package BusinessCentral\API\Repositories
 */
class ShippingMethodsRepository extends RepositoryAbstract
{
    /**
     * @return \Illuminate\Support\Collection
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function get()
    {
        $response = $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/shipmentMethods()'
        );

        $shippingMethods = collect();

        foreach ($response->value as $item) {
            if (!is_string($item)) {
                $shippingMethods->push(new BusinessCentralShippingMethod((array)$item));
            }
        }

        return $shippingMethods;
    }

    public function getByShippingSku($sku) {
        $daalderShippingMethod = DaalderShippingMethod::where('sku', '=', $sku)->first();
        if($daalderShippingMethod) {
            return BusinessCentralShippingMethod::where('shipping_method_id', '=', $daalderShippingMethod->id)->first();
        }

        return null;
    }
}
