<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use Daalder\BusinessCentral\Models\OrderBusinessCentral;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Pionect\Backoffice\Models\Order\Order;
use Pionect\Backoffice\Models\Order\Orderrow;
use Pionect\Backoffice\Models\Product\Product;
use Pionect\Backoffice\Models\Product\ProductOption;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class SalesOrderRepository extends RepositoryAbstract
{
    public string $objectName = 'salesOrders';

    public function create(Order $order): ?\stdClass
    {
        // First create customer
        $this->client->customer()->create($order->customer);

        $reference = $this->referenceRepository->getReference(new OrderBusinessCentral(['order_id' => $order->id]));
        if ($reference) {
            return $this->update($order);
        }

        $resource = new \BusinessCentral\API\Resources\SalesOrder($order);

        // Unset discount because order lines first needs to be created
        $salesOrder = $resource->resolve();
        unset($salesOrder['discountAmount']);

        logger($salesOrder);

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrders',
            $salesOrder
        );

        $this->storeReference(new OrderBusinessCentral([
            'order_id' => $order->id,
            'business_central_id' => $response->id,
        ]));

        $optionProduct = Product::query()->where('sku', '0001')->first();

        $productOptions = [];
        foreach ($order->orderrows as $row) {
            if (json_decode($row->productoptions)) {
                foreach (json_decode($row->productoptions) as $productOption) {
                    $productOptions[] = Orderrow::query()->newModelInstance([
                        'product_id' => $optionProduct->id,
                        'order_id' => $order->id,
                        'name' => $row->sku.' - Option - '.$productOption->label.': '.$productOption->print_value,
                        'sku' => $optionProduct->sku,
                        'amount' => 1,
                        'price' => 0,
                        'productoptions' => null,
                        'vat_rate' => 0,
                        'vat_type' => null,
                        'vat_rate_name' => 0,
                    ]);

                    // Check if productvalue is found
                    $productValue = ProductOption::where('id', $productOption->option_value)->first();
                    if ($productValue && ! empty($productValue->reference)) {
                        // Replace name of order row with one that includes the productvalue reference
                        $lastOrderRow = array_last($productOptions);
                        $lastOrderRow->name = $row->sku.'-'.$productValue->reference.' - '.$productOption->label.': '.$productOption->print_value;
                        $lastOrderRow->save();
                    }
                }
            }
        }

        foreach ($order->orderrows as $row) {
            $this->client->salesOrderLine()->create($row, $response->id);
        }

        foreach ($productOptions as $row) {
            $this->client->salesOrderLine()->create($row, $response->id, $row->name);
        }

        // if the order has discount then update.
        if ($order->hasDiscount()) {
            $this->update($order);
        }

        return $response;
    }

    public function findByNumber(string $orderNumber): ?\stdClass
    {
        return $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrders?$filter=number eq \''.$orderNumber.'\''
        );
    }

    public function update(Order $order): ?\stdClass
    {
        /** @var ProductBusinessCentral $reference */
        $reference = $this->referenceRepository->getReference(new OrderBusinessCentral(['order_id' => $order->id]));

        if ($reference) {
            $resource = new \BusinessCentral\API\Resources\SalesOrder($order);

            return $this->client->patch(
                config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrders('.$reference->business_central_id.')',
                $resource->resolve()
            );
        }

        return null;
    }

    /**
     * @return null
     */
    public function delete(Order $order)
    {
        /** @var ProductBusinessCentral $reference */
        $reference = $this->referenceRepository->getReference(new OrderBusinessCentral(['order_id' => $order->id]));

        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrders('.$reference->business_central_id.')'
        );
    }
}
