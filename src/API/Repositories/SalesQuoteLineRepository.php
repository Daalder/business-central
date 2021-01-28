<?php

namespace BusinessCentral\API\Repositories;


use BusinessCentral\API\Resources\SalesQuoteLine;
use BusinessCentral\Models\ProductBusinessCentral;
use BusinessCentral\Models\SalesQuoteLineBusinessCentral;
use Pionect\Backoffice\Models\Order\Orderrow;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class SalesQuoteLineRepository extends RepositoryAbstract
{
    public $objectName = 'salesQuoteLines';

    /**
     * @param  \Pionect\Backoffice\Models\Order\Orderrow  $row
     * @param                                           $businessCentralOrderReference
     * @return void
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function create(Orderrow $row, $businessCentralOrderReference)
    {
        /** @var ProductBusinessCentral $productBusinessCentral */
        $productBusinessCentral = ProductBusinessCentral::where('product_id', $row->product_id)->first();

        $resource       = new SalesQuoteLine($row);
        $salesQuoteLine = $resource->resolve();

        // If product not found in reference table let's create it.
        if (!$productBusinessCentral) {
            $productResponse          = $this->client->item()->create($row->product()->withTrashed()->first());
            $salesQuoteLine['itemId'] = (string) $productResponse->id;
        } else {
            $salesQuoteLine['itemId'] = (string) $productBusinessCentral->business_central_id;
        }

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesQuotes('.$businessCentralOrderReference.')/salesQuoteLines', $salesQuoteLine
        );

        $this->storeReference(new SalesQuoteLineBusinessCentral([
            'order_row_id'        => $row->id,
            'business_central_id' => $response->sequence
        ]));
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
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesQuoteLines('.$ref.')', $params
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
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesQuoteLines('.$ref.')'
        );
    }

}
