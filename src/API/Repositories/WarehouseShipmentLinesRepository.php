<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\Models\Shipment;
use Daalder\BusinessCentral\Models\WarehouseShipmentLine;
use Illuminate\Support\Collection;

/**
 * Class SalesQuoteRepository
 *
 * @package BusinessCentral\API\Repositories
 */
class WarehouseShipmentLinesRepository extends RepositoryAbstract
{
    /**
     * @param Shipment $shipment
     * @return Collection
     */
    public function get(Shipment $shipment): Collection
    {
        $response = $this->client->get(
            'https://api.businesscentral.dynamics.com/v2.0/962abadf-3251-42b9-bf80-f5867aedac7a/productioncopy/api/nubuiten/api/v1.0/companies(763d0c0f-7655-45cd-9c1f-77a7507be513)/warehouseShipments('.$shipment->business_central_id.')/warehouseShipmentLines'
        );

        $warehouseShipmentLines = collect();

        foreach ($response->value as $item) {
            if (! is_string($item)) {
                $warehouseShipmentLines->push(new WarehouseShipmentLine((array) $item));
            }
        }

        return $warehouseShipmentLines;
    }
}
