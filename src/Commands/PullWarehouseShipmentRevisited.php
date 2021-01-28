<?php

namespace BusinessCentral\Commands;

use BusinessCentral\API\Repositories\SalesOrderRepository;
use BusinessCentral\API\Repositories\WarehouseShipmentRepository;
use BusinessCentral\Models\OrderBusinessCentral;
use Trello\Jobs\MakeTrelloCard;
use Trello\Models\QueueTrelloCard;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;


class PullWarehouseShipmentRevisited extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bc:create-warehouse-shipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull warehouse shipment from Business Central';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  WarehouseShipmentRepository  $warehouseShipmentRepository
     * @param  SalesOrderRepository  $salesOrderRepository
     * @return mixed
     */
    public function handle(
        WarehouseShipmentRepository $warehouseShipmentRepository,
        SalesOrderRepository $salesOrderRepository
    ) {
        $warehouseShipments     = $warehouseShipmentRepository->get();
        $warehouseShipmentsTemp = array_unique(array_column($warehouseShipments, 'order_number'));
        $warehouseShipments     = array_intersect_key($warehouseShipments, $warehouseShipmentsTemp);

        $salesOrders = $this->getSalesOrders($salesOrderRepository, $warehouseShipments);
        $orders      = $this->getOrders($salesOrders);

        $businessCentralLink = '';
        /** @var OrderBusinessCentral $businessCentral */
        $businessCentral = OrderBusinessCentral::where('order_id',$this->order->id)->first();
        if($businessCentral) {
            $url = 'https://businesscentral.dynamics.com/?company=NuBuiten&page=42&filter='.urlencode('\'Id\' IS \''.$businessCentral->business_central_id.'\'');
            $businessCentralLink = '[Business Central 365]('.$url.')';
        }

        foreach ($orders as $order) {
            $queueTrelloCard = new QueueTrelloCard($order, config('trello.shipment_list_id', '56ec1a6fc882f18a2082bf46'), [
                'business_central_link' => $businessCentralLink
            ]);
            $job             = (new MakeTrelloCard($queueTrelloCard, $order))->onQueue('high');
            $this->dispatch($job);
        }
    }

    private function getSalesOrders(SalesOrderRepository $salesOrderRepository, $warehouseShipments)
    {
        $salesOrders = [];
        foreach ($warehouseShipments as $warehouseShipment) {
            $salesOrder = $salesOrderRepository->findByNumber($warehouseShipment['order_number']);

            if (!empty($salesOrder->value)) {
                $salesOrders[] = $salesOrder->value[0];
            }
        }

        return $salesOrders;
    }

    private function getOrders($salesOrders)
    {
        $orders = [];
        foreach ($salesOrders as $salesOrder) {
            $order = $this->getOrderRecord($salesOrder);

            if ($order) {
                $orders[] = $order;
            }
        }

        return $orders;
    }

    /**
     * @param $salesOrder
     * @return mixed
     */
    private function getOrderRecord($salesOrder)
    {
        $orderBusinessCentral = OrderBusinessCentral::where('business_central_id', $salesOrder->id)->first();

        if (!$orderBusinessCentral) {
            return null;
        }

        return $orderBusinessCentral->order;
    }
}
