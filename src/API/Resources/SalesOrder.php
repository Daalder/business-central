<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Daalder\BusinessCentral\API\Repositories\ShippingMethodsRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class SalesOrder
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\Order\Order
 */
class SalesOrder extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $shippingMethodsRespository = resolve(ShippingMethodsRepository::class);
        $bcShippingMethod = $shippingMethodsRespository->getByShippingSku($this->getShippingSku());

        return [
            //'number' => (string)$this->orderid,
            'externalDocumentNumber' => (string) $this->id,
            'orderDate' => $this->date->toDateString(),
            'customerNumber' => (string) $this->customer_id,
            //'currencyCode' => "EURO",
            'paymentTermsId' => (string) $this->getPaymentTerms(),
            'discountAmount' => $this->getDiscount(),
            'shipToAddressLine1' => str_limit($this->address.' '.$this->housenumber, 47),
            'shipToCity' => ucfirst($this->city),
            'shipToCountry' => $this->country_code ?: Customer::default_country, // Default to NL is needed for API template.
            'shipToPostCode' => $this->postalcode,
            //'shipmentMethodId'          => $bcShippingMethod->business_central_id,
        ];
    }

    private function getPaymentTerms(): string
    {
        if ($this->payment) {
            switch ($this->payment->method_id) {
                case 7: // iDeal
                case 8: // Mastercard
                case 9: // Mistercash
                case 13: // Visa
                    return '60f881ba-b359-4b9a-87a7-5503d0342761';
                    break;
                case 2: // Wire
                    return 'fbe87a32-d5e2-4ba3-8528-b23c4593c915';
                    break;
                case 3: // Pin
                case 5: // Contant
                    return '4e826475-7486-4941-8bfe-b530438f773b';
                    break;
                case 11: // PayPal
                    return '0b4ebcd1-9829-40ff-ae24-447984b1b8c8';
                    break;
                default:
                    return 'fbe87a32-d5e2-4ba3-8528-b23c4593c915';
                    break;
            }
        } else {
            return 'fbe87a32-d5e2-4ba3-8528-b23c4593c915';
        }
    }
}

//{
//  "id": "id-value",
//  "number": "1009",
//  "orderDate": "2015-12-31",
//  "customerNumber": "GL00000008",
//  "currencyCode": "GBP",
//  "paymentTerms": "COD"
//}
