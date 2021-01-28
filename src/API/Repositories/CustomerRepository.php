<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\Commands\PullFromBusinessCentral;
use Daalder\BusinessCentral\Models\CustomerBusinessCentral;
use Pionect\Backoffice\Models\Customer\Customer;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class CustomerRepository extends RepositoryAbstract
{
    public $objectName = 'customers';

    /**
     * @param Customer $customer
     * @return \stdClass|null
     */
    public function create(Customer $customer): ?\stdClass
    {
        $resource = new \Daalder\BusinessCentral\API\Resources\Customer($customer);

        // If we have a reference then try to update.
        if ($this->referenceRepository->getReference(new CustomerBusinessCentral(['customer_id' => $customer->id]))) {
            return $this->update($customer);
        }

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/customers', $resource->resolve()
        );

        $this->storeReference(new CustomerBusinessCentral([
            'customer_id' => $customer->id,
            'business_central_id' => $response->id,
        ]));

        return $response;
    }

    /**
     * @param Customer $customer
     * @return \stdClass|null
     */
    public function update(Customer $customer): ?\stdClass
    {
        $resource = new \Daalder\BusinessCentral\API\Resources\Customer($customer);

        // Unset displayName because we don't have 2 way sync yet and BC changes are leading.
        $customerResource = $resource->resolve();
        unset($customerResource['displayName']);

        /** @var CustomerBusinessCentral $reference */
        $reference = $this->referenceRepository->getReference(new CustomerBusinessCentral(['customer_id' => $customer->id]));

        if ($reference) {
            return $this->client->patch(
                config('business-central.endpoint').'companies('.config('business-central.companyId').')/customers('.$reference->business_central_id.')', $customerResource
            );
        }

        return null;
    }

    /**
     * @param Customer $customer
     * @return null
     */
    public function delete(Customer $customer)
    {
        /** @var CustomerBusinessCentral $reference */
        $reference = $this->referenceRepository->getReference(new CustomerBusinessCentral(['customer_id' => $customer->id]));

        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/customers('.$reference->business_central_id.')'
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
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/customers?$top='.$top.'&$skip='.$skip
        );

        foreach ($response->value as $item) {
            $customer = Customer::find($item->number);
            if ($customer) {
                if (! $this->referenceRepository->getReference(new CustomerBusinessCentral(['customer_id' => $customer->id]))) {
                    $this->storeReference(new CustomerBusinessCentral([
                        'customer_id' => $customer->id,
                        'business_central_id' => $item->id,
                    ]));
                }
            } else {
                $command->error('Customer not found: '.$item->number);
            }
        }

        return $response;
    }

    /**
     * @param $guid
     * @return \stdClass|null
     */
    public function get($guid): ?\stdClass
    {
        return $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/customers('.$guid.')'
        );
    }
}
