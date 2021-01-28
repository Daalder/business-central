<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Resources\Daalder;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Order
 *
 * @package BusinessCentral\API\Resources\Daalder
 */
class Customer extends JsonResource
{
    /**
     * @var array
     */
    private array $customer = [];

    /**
     * @return array
     */
    public function toArray(\Illuminate\Http\Request $request): array
    {
        if ($request->get('taxRegistrationNumber')) {
            $this->customer['company_name'] = $request->get('displayName');
            $this->customer['company_vat'] = $request->get('taxRegistrationNumber');
        }

        return array_merge([
            'firstname' => $request->get('displayName'),
            'lastname' => '-',
            'email' => $request->get('email'),
            'delivery_postalcode' => $request->get('address.postalCode'),
            'delivery_city' => $request->get('address.city'),
            'delivery_country_code' => $request->get('address.countryLetterCode'),
            'invoice_postalcode' => $request->get('address.postalCode'),
            'invoice_city' => $request->get('address.city'),
            'invoice_country_code' => $request->get('address.countryLetterCode'),
            'contact_type' => $request->get('type') === 'Person' ? 'private' : 'company',

        ], $this->customer);
    }
}
