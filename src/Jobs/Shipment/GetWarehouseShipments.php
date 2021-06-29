<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Jobs\Shipment;

use App\Models\Shipping\ShippingProvider;
use App\Repositories\ShipmentProviderRepository;
use Carbon\Carbon;
use Daalder\BusinessCentral\API\Repositories\WarehouseShipmentRepository;
use Daalder\BusinessCentral\Models\OrderBusinessCentral;
use Daalder\BusinessCentral\Models\Shipment;
use Daalder\BusinessCentral\Models\WarehouseShipment;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Pionect\Daalder\Models\Address\Repositories\AddressRepository;

class GetWarehouseShipments
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private WarehouseShipmentRepository $warehouseShipmentRepository;
    private AddressRepository $addressRepository;
    private ShipmentProviderRepository $shipmentProviderRepository;

    /**
     * Create a new job instance.
     */
    public function __construct(WarehouseShipmentRepository $warehouseShipmentRepository, AddressRepository $addressRepository, ShipmentProviderRepository $shipmentProviderRepository)
    {
        $this->warehouseShipmentRepository = $warehouseShipmentRepository;
        $this->addressRepository = $addressRepository;
        $this->shipmentProviderRepository = $shipmentProviderRepository;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $referenceRepository = app(ReferenceRepository::class);

        $shipments = $this->warehouseShipmentRepository->get();

        $this->removeShipmentsThatAreNotInBC($shipments);

        /** @var WarehouseShipment $bcShipment */
        foreach ($shipments as $bcShipment) {
            $reference = OrderBusinessCentral::where('business_central_id', strtolower(str_replace(['{','}'], '', $bcShipment->salesOrderId)))->first();

            /** @var Shipment $shipment */
            $shipment = Shipment::updateOrCreate(['reference' => $bcShipment->no], [
                'business_central_id' => $bcShipment->id,
                'phone' => $bcShipment->customerPhone,
                'email' => $bcShipment->customerEmail,
                'number_of_colli' => $bcShipment->noOfColli,
                'trip_number' => $bcShipment->tripnumber ?: 'none',
                'week_number' => $bcShipment->weeknumber,
                'work_description' => $bcShipment->workDescription,
                'load_status' => $bcShipment->loadStatus,
                'customer_name' => $bcShipment->customerName,
                'shipment_method_code' => $bcShipment->shipmentMethodCode,
                'salesperson_code' => $bcShipment->salespersonCode,
                'planned_delivery_date' => $bcShipment->PlannedDeliveryDate,
                'sent_as_email_cust' => $bcShipment->SentasEmailCust,
                'shipment_date' => $bcShipment->ShipmentDate,
                'pakbon_printed_at' => Carbon::createFromFormat('Y-m-d\TH:i:s+', $bcShipment->PakbonPrintedAt),
                'picking_list_printed_at' => Carbon::createFromFormat('Y-m-d\TH:i:s+', $bcShipment->PickingListPrintedAt),
                'last_email_sent_time_cust' => Carbon::createFromFormat('Y-m-d\TH:i:s+', $bcShipment->LastEmailSentTimeCust),
                'last_email_sent_time_complete' => Carbon::createFromFormat('Y-m-d\TH:i:s+', $bcShipment->LastEmailSentTimeComplete),
                'last_email_sent_time' => Carbon::createFromFormat('Y-m-d\TH:i:s+', $bcShipment->LastEmailSentTime),
                'sent_as_email' => $bcShipment->SentAsEmail,
                'sent_as_email_complete' => $bcShipment->SentAsEmailComplete,
                'external_document_no' => $bcShipment->ExternalDocumentNo,
                'sort_order' => $bcShipment->sortOrder,
            ]);

            if ($reference) {
                $shipment->order_id = optional($reference->order)->id;
            }

            // TODO: create should support hash in core and not create duplicate address.
            $address = $this->addressRepository->create([
                'address_line_1' => $bcShipment->shippingAddress,
                'postalcode' => $bcShipment->shippingAddressPostalCode,
                'city' => $bcShipment->shippingAddressPostalCity,
                'country_code' => $bcShipment->shippingAddressPostalCountry,
            ]);

            $provider = ShippingProvider::query()->where('code', $bcShipment->shippingAgentCode)->first();
            if ($provider) {
                $shipment->provider()->associate($provider);
            }
            $shipment->shippingAddress()->associate($address);
            $shipment->save();

            dispatch(new GetWarehouseShipmentLines($shipment));
        }
    }

    private function removeShipmentsThatAreNotInBC(\Illuminate\Support\Collection $bcShipments): void
    {
        $localShipments = Shipment::all()->pluck('reference')->toArray();
        $remoteShipments = $bcShipments->pluck('no')->toArray();

        $diff = array_intersect($localShipments, $remoteShipments);

        Shipment::query()->whereNotIn('reference', $diff)->delete();
    }
}
