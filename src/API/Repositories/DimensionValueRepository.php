<?php

namespace BusinessCentral\API\Repositories;

use BusinessCentral\Models\GroupBusinessCentral;
use Pionect\Backoffice\Models\Product\Group;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class DimensionValueRepository extends RepositoryAbstract
{
    public $objectName = 'item';

    const GROUP_DIMENSION = 'e6824ae1-3a1c-4ccd-95e2-632cb16789f9';

    /**
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function pullReferences()
    {

        $response = $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/dimensions(e6824ae1-3a1c-4ccd-95e2-632cb16789f9)/dimensionValues'
        );


        foreach ($response->value as $dimensionValue) {

            $group = Group::where('code', $dimensionValue->code)->first();

            if ($group) {
                $this->storeReference(new GroupBusinessCentral([
                    'group_id'            => $group->id,
                    'business_central_id' => $dimensionValue->id
                ]));
            }
        }

        return $response;
    }


}