<?php

namespace BusinessCentral\API\Repositories;


use Pionect\Backoffice\Models\Product\Product;
use BusinessCentral\API\Resources\salesOrderLine;
use BusinessCentral\Models\OrderRowBusinessCentral;
use BusinessCentral\Models\ProductBusinessCentral;
use Pionect\Backoffice\Models\Order\Orderrow;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class SalesOrderLineRepository extends RepositoryAbstract
{
    public $objectName = 'salesOrderLines';

    /**
     * @param  \Pionect\Backoffice\Models\Order\Orderrow  $row
     * @param                                           $businessCentralOrderReference
     * @return void
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function create(Orderrow $row, $businessCentralOrderReference, string $overwriteRowDescription = null)
    {
        /** @var ProductBusinessCentral $productBusinessCentral */
        $productBusinessCentral = ProductBusinessCentral::where('product_id', $row->product_id)->first();

        $resource       = new SalesOrderLine($row, $overwriteRowDescription);
        $salesOrderLine = $resource->resolve();

        // If product not found in reference table let's create it.
        if (!$productBusinessCentral) {
            $productResponse          = $this->client->item()->create($row->product()->withTrashed()->first());
            $salesOrderLine['itemId'] = (string) $productResponse->id;
        } else {
            $salesOrderLine['itemId'] = (string) $productBusinessCentral->business_central_id;
        }


        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrders('.$businessCentralOrderReference.')/salesOrderLines', $salesOrderLine
        );

        // If not processing product options (row instances)
        if($row->id) {
            $this->storeReference(new OrderRowBusinessCentral([
                'order_row_id'        => $row->id,
                'business_central_id' => $response->sequence
            ]));
        }
    }

    /**
     * @param  array  $params
     * @param       $ref
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function update(array $params, $ref)
    {
        return $this->client->patch(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrderLines('.$ref.')', $params
        );
    }

    /**
     * @param $ref
     * @return null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function delete($ref)
    {
        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrderLines('.$ref.')'
        );
    }

}
