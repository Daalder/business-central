<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Shipment;

use Daalder\BusinessCentral\API\Repositories\WarehouseShipmentLinesRepository;
use Daalder\BusinessCentral\API\Repositories\WarehouseShipmentRepository;
use Daalder\BusinessCentral\Models\Shipment;
use Daalder\BusinessCentral\Models\ShipmentLine;
use Daalder\BusinessCentral\Models\WarehouseShipmentLine;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetWarehouseShipmentLines
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private WarehouseShipmentRepository $warehouseShipmentRepository;
    private WarehouseShipmentLinesRepository $warehouseShipmentLinesRepository;
    private Shipment $shipment;

    /**
     * GetWarehouseShipmentLines constructor.
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $shipmentLines = resolve(WarehouseShipmentLinesRepository::class)->get($this->shipment);

        /** @var WarehouseShipmentLine $line */
        foreach ($shipmentLines as $line) {

            /** @var ShipmentLine $shipmentLine */
            $shipmentLine = ShipmentLine::updateOrCreate(['shipment_reference' => $line->warehouseShipmentNo, 'line' => $line->lineNo], [
                'shipment_business_central_id' => $line->warehouseShipmentId,
                'order_reference' => $line->sourceDocumentNo,
                'order_business_central_id' => $line->sourceDocumentId,
                'sku' => $line->itemNo,
                'amount' => $line->qty,
            ]);
            $shipmentLine->save();
        }
    }
}
