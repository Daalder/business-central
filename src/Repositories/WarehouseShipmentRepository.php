<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use Daalder\BusinessCentral\Models\WarehouseShipment;
use Illuminate\Support\Collection;

/**
 * Class SalesQuoteRepository
 *
 * @package BusinessCentral\API\Repositories
 */
class WarehouseShipmentRepository extends RepositoryAbstract
{
    public function get(): Collection
    {
        $response = $this->client->get(
            'https://api.businesscentral.dynamics.com/v2.0/962abadf-3251-42b9-bf80-f5867aedac7a/productioncopy/api/nubuiten/api/v1.0/companies(763d0c0f-7655-45cd-9c1f-77a7507be513)/warehouseShipments'
        );

        $warehouseShipments = collect();

        foreach ($response->value as $item) {
            if (! is_string($item)) {
                $warehouseShipments->push(new WarehouseShipment((array) $item));
            }
        }

        return $warehouseShipments;
    }

    public function getGroupedByList(): Collection
    {
        $shipments = $this->get();
        return $shipments->groupBy('tripnumber');
    }

    public function update($business_central_id, $payload): ?\stdClass
    {
        return $this->client->patch(
            'https://api.businesscentral.dynamics.com/v2.0/962abadf-3251-42b9-bf80-f5867aedac7a/productioncopy/api/nubuiten/api/v1.0/companies(763d0c0f-7655-45cd-9c1f-77a7507be513)/warehouseShipments('.$business_central_id.')',
            $payload
        );
    }
}