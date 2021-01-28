<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources;

use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Customer
 *
 * @package BusinessCentral\API\Resources
 *
 * @mixin \Pionect\Backoffice\Models\Customer\Customer
 */
class Customer extends Resource
{
    const default_country = 'NL';

    /**
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        return [
            'number' => (string) $this->id,
            'displayName' => str_limit($this->present()->fullName, 47),
            'type' => $this->setType(),
            'addressLine1' => str_limit($this->invoice_address, 47),
            'city' => ucfirst($this->invoice_city),
            'country' => $this->invoice_country_code ?: Customer::default_country, // Default to NL is needed for API template.
            'postalCode' => $this->invoice_postalcode,
            'phoneNumber' => preg_replace('/[^0-9,+]/', '', $this->telephone ?? $this->mobile), //use pregreplace to avoid BC chocking from non-numeric char in telephone field
            'email' => preg_replace("/\s+/", '', $this->email),
            'taxRegistrationNumber' => $this->company()->exists() ? $this->company->vatnumber : '',
        ];
    }

    private function setType(): string
    {
        return $this->getContactTypeAttribute() === 'company' ? 'Company' : 'Person';
    }
}

//{
//  "number": "10000",
//  "displayName": "Coho Winery",
//  "type": "Company",
//  "address": {
//    "street": "192 Market Square",
//    "city": "Atlanta",
//    "state": "GA",
//    "countryLetterCode": "US",
//    "postalCode": "31772"
//  },
//  "phoneNumber": "",
//  "email": "jim.glynn@cronuscorp.net",
//  "website": "",
//  "taxLiable": true,
//  "taxAreaId": "taxAreaId-value",
//  "taxAreaDisplayName": "tax area",
//  "taxRegistrationNumber": "28012001T",
//  "currencyId": "currencyId-value",
//  "currencyCode": "USD",
//  "paymentTermsId": "paymentTermsId-value",
//  "paymentTerms": {
//    "code": "1M(8D)",
//    "description": "1 Month/2% 8 days"
//  },
//  "shipmentMethodId": "shipmentMethodId-value",
//  "shipmentMethod": null,
//  "paymentMethodId": "paymentMethodId-value",
//  "paymentMethod": {
//    "code": "BANK",
//    "description": "Bank Transfer"
//  },
//  "blocked": " ",
//  "overdueAmount": 0,
//  "totalSalesExcludingTax": 0,
//}
