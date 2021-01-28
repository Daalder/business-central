<?php

namespace BusinessCentral\Jobs\Shipment;

use BusinessCentral\Models\Shipment;
use BusinessCentral\API\Repositories\WarehouseShipmentLinesRepository;
use BusinessCentral\API\Repositories\WarehouseShipmentRepository;
use BusinessCentral\Models\ShipmentLine;
use BusinessCentral\Models\WarehouseShipmentLine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class GetWarehouseShipmentLines
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var WarehouseShipmentRepository
     */
    private $warehouseShipmentRepository;
    /**
     * @var WarehouseShipmentLinesRepository
     */
    private $warehouseShipmentLinesRepository;
    /**
     * @var Shipment
     */
    private $shipment;

    /**
     * GetWarehouseShipmentLines constructor.
     * @param Shipment $shipment
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $shipmentLines = resolve(WarehouseShipmentLinesRepository::class)->get($this->shipment);

        /** @var WarehouseShipmentLine $line */
        foreach ($shipmentLines as $line){

            /** @var ShipmentLine $shipmentLine */
            $shipmentLine = ShipmentLine::updateOrCreate(['shipment_reference' => $line->warehouseShipmentNo, 'line' => $line->lineNo], [
                'shipment_business_central_id'=>$line->warehouseShipmentId,
                'order_reference'=>$line->sourceDocumentNo,
                'order_business_central_id'=>$line->sourceDocumentId,
                'sku'=>$line->itemNo,
                'amount'=>$line->qty
            ]);
            $shipmentLine->save();
        }
    }
}
