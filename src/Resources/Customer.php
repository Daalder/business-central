<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Customer
 *
 * @package BusinessCentral\Resources
 *
 * @mixin \Pionect\Daalder\Models\Customer\Customer
 */
class Customer extends JsonResource
{
    const default_country = 'NL';

    /**
     * @return array
     */
    public function toArray(Request $request): array
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
