<?php

namespace BusinessCentral\API\Resources\Daalder;


use Illuminate\Http\Resources\Json\Resource;

/**
 * Class Order
 *
 * @package BusinessCentral\API\Resources\Daalder
 */
class Customer extends Resource
{
    /**
     * @var array
     */
    private $customer = [];

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($request->get('taxRegistrationNumber')) {
            $this->customer['company_name'] = $request->get('displayName');
            $this->customer['company_vat']  = $request->get('taxRegistrationNumber');
        }

        return array_merge([
            'firstname'             => $request->get('displayName'),
            'lastname'              => '-',
            'email'                 => $request->get('email'),
            'delivery_postalcode'   => $request->get('address.postalCode'),
            'delivery_city'         => $request->get('address.city'),
            'delivery_country_code' => $request->get('address.countryLetterCode'),
            'invoice_postalcode'    => $request->get('address.postalCode'),
            'invoice_city'          => $request->get('address.city'),
            'invoice_country_code'  => $request->get('address.countryLetterCode'),
            'contact_type'          => ($request->get('type') == 'Person') ? 'private' : 'company'

        ], $this->customer);
    }
}
