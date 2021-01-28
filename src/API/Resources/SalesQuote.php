<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Pionect\Backoffice\Models\Order\Order;

/**
 * Class SalesQuote
 *
 * @package BusinessCentral\API\Resources
 * @mixin Order
 */
class SalesQuote extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'externalDocumentNumber' => (string) $this->orderid,
            'documentDate' => $this->date ? $this->date->toDateString() : null,
            'customerNumber' => (string) $this->customer_id,
            'paymentTermsId' => (string) $this->getPaymentTerms(),
            'discountAmount' => $this->getDiscount(),
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
