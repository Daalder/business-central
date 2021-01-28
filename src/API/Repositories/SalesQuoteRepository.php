<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\API\Resources\SalesQuote;
use Daalder\BusinessCentral\Models\SalesQuoteBusinessCentral;
use Pionect\Backoffice\Models\Order\Order;

/**
 * Class SalesQuoteRepository
 *
 * @package Daalder\BusinessCentral\API\Repositories
 */
class SalesQuoteRepository extends RepositoryAbstract
{
    /**
     * @return bool|\stdClass|null
     *
     * @throws \Exception
     */
    public function create(Order $order)
    {
        // First create customer
        $this->client->customer()->create($order->customer);

        $reference = $this->referenceRepository->getReference(new SalesQuoteBusinessCentral(['order_id' => $order->id]));
        if ($reference) {
            return $this->update($order);
        }

        $resource = new SalesQuote($order);

        // Unset discount because order lines first needs to be created
        $salesQuote = $resource->resolve();
        unset($salesQuote['discountAmount']);

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesQuotes', $salesQuote
        );

        $this->storeReference(new SalesQuoteBusinessCentral([
            'order_id' => $order->id,
            'business_central_id' => $response->id,
        ]));

        foreach ($order->orderrows as $row) {
            $this->client->salesQuoteLine()->create($row, $response->id);
        }

        if ($order->hasDiscount()) {
            $this->update($order);
        }
    }

    /**
     * @return false|\stdClass|null
     */
    public function update(Order $order)
    {
        $reference = $this->referenceRepository->getReference(new SalesQuoteBusinessCentral(['order_id' => $order->id]));

        if ($reference) {
            $resource = new SalesQuote($order);

            return $this->client->patch(
                config('business-central.endpoint').'companies('.config('business-central.companyId').')/salesQuotes('.$reference->business_central_id.')', $resource->resolve()
            );
        }

        return false;
    }
}
