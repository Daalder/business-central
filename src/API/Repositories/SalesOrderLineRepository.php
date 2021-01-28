<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\API\Resources\salesOrderLine;
use Daalder\BusinessCentral\Models\OrderRowBusinessCentral;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
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
     * @param                                           $businessCentralOrderReference
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function create(Orderrow $row, $businessCentralOrderReference, ?string $overwriteRowDescription = null): void
    {
        /** @var ProductBusinessCentral $productBusinessCentral */
        $productBusinessCentral = ProductBusinessCentral::where('product_id', $row->product_id)->first();

        $resource = new SalesOrderLine($row, $overwriteRowDescription);
        $salesOrderLine = $resource->resolve();

        // If product not found in reference table let's create it.
        if (! $productBusinessCentral) {
            $productResponse = $this->client->item()->create($row->product()->withTrashed()->first());
            $salesOrderLine['itemId'] = (string) $productResponse->id;
        } else {
            $salesOrderLine['itemId'] = (string) $productBusinessCentral->business_central_id;
        }

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrders('.$businessCentralOrderReference.')/salesOrderLines', $salesOrderLine
        );

        // If not processing product options (row instances)
        if ($row->id) {
            $this->storeReference(new OrderRowBusinessCentral([
                'order_row_id' => $row->id,
                'business_central_id' => $response->sequence,
            ]));
        }
    }

    /**
     * @param  array  $params
     * @param       $ref
     *
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function update(array $params, $ref): ?\stdClass
    {
        return $this->client->patch(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesOrderLines('.$ref.')', $params
        );
    }

    /**
     * @param $ref
     *
     * @return null
     *
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
