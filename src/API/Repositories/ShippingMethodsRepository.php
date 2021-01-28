<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\Models\ShippingMethod as BusinessCentralShippingMethod;
use Illuminate\Support\Collection;
use Pionect\Backoffice\Models\Shipping\ShippingMethod as DaalderShippingMethod;

/**
 * Class ShippingMethodsRepository
 *
 * @package BusinessCentral\API\Repositories
 */
class ShippingMethodsRepository extends RepositoryAbstract
{
    /**
     * @return Collection
     */
    public function get(): Collection
    {
        $response = $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/shipmentMethods()'
        );

        $shippingMethods = collect();

        foreach ($response->value as $item) {
            if (! is_string($item)) {
                $shippingMethods->push(new BusinessCentralShippingMethod((array) $item));
            }
        }

        return $shippingMethods;
    }

    /**
     * @param $sku
     * @return null
     */
    public function getByShippingSku($sku)
    {
        $daalderShippingMethod = DaalderShippingMethod::query()->where('sku', '=', $sku)->first();
        if ($daalderShippingMethod) {
            return BusinessCentralShippingMethod::query()->where('shipping_method_id', '=', $daalderShippingMethod->id)->first();
        }

        return null;
    }
}
