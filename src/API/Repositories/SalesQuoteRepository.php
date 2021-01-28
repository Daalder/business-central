<?php

namespace BusinessCentral\API\Repositories;

use BusinessCentral\Models\SalesQuoteBusinessCentral;
use Pionect\Backoffice\Models\Order\Order;

/**
 * Class SalesQuoteRepository
 *
 * @package BusinessCentral\API\Repositories
 */
class SalesQuoteRepository extends RepositoryAbstract
{

    /**
     * @param  \Pionect\Backoffice\Models\Order\Order  $order
     * @return bool|\stdClass|null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function create(Order $order)
    {
        // First create customer
        $this->client->customer()->create($order->customer);

        $reference = $this->referenceRepository->getReference(new SalesQuoteBusinessCentral(['order_id' => $order->id]));
        if ($reference) {
            return $this->update($order);
        }

        $resource = new \BusinessCentral\API\Resources\SalesQuote($order);

        // Unset discount because order lines first needs to be created
        $salesQuote = $resource->resolve();
        unset($salesQuote['discountAmount']);

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesQuotes', $salesQuote
        );

        $this->storeReference(new SalesQuoteBusinessCentral([
            'order_id'            => $order->id,
            'business_central_id' => $response->id
        ]));

        foreach ($order->orderrows as $row) {
            $this->client->salesQuoteLine()->create($row, $response->id);
        }

        if ($order->hasDiscount()) {
            $this->update($order);
        }
    }

    /**
     * @param  Order  $order
     * @return bool|\stdClass|null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function update(Order $order)
    {
        $reference = $this->referenceRepository->getReference(new SalesQuoteBusinessCentral(['order_id' => $order->id]));

        if ($reference) {
            $resource = new \BusinessCentral\API\Resources\SalesQuote($order);

            return $this->client->patch(
                config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesQuotes('.$reference->business_central_id.')', $resource->resolve()
            );
        }

        return false;
    }
}
