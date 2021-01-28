<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

/**
 * Class SalesQuoteRepository
 *
 * @package BusinessCentral\API\Repositories
 */
class WarehouseShipmentRepository extends RepositoryAbstract
{
    /**
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function get(): \Illuminate\Support\Collection
    {
        $response = $this->client->get(
            'https://api.businesscentral.dynamics.com/v2.0/962abadf-3251-42b9-bf80-f5867aedac7a/productioncopy/api/nubuiten/api/v1.0/companies(763d0c0f-7655-45cd-9c1f-77a7507be513)/warehouseShipments'
        );

        $warehouseShipments = collect();

        foreach ($response->value as $item) {
            if (! is_string($item)) {
                $warehouseShipments->push(new \BusinessCentral\Models\WarehouseShipment((array) $item));
            }
        }

        return $warehouseShipments;
    }

    /**
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function getGroupedByList(): \Illuminate\Support\Collection
    {
        $shipments = $this->get();
        return $shipments->groupBy('tripnumber');
    }

    /**
     * @param $business_central_id
     * @param $payload
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function update($business_central_id, $payload): ?\stdClass
    {
        return $this->client->patch(
            'https://api.businesscentral.dynamics.com/v2.0/962abadf-3251-42b9-bf80-f5867aedac7a/productioncopy/api/nubuiten/api/v1.0/companies(763d0c0f-7655-45cd-9c1f-77a7507be513)/warehouseShipments('.$business_central_id.')',
            $payload
        );
    }
}
