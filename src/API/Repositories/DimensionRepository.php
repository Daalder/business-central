<?php

namespace BusinessCentral\API\Repositories;

use BusinessCentral\Models\DefaultDimension as DefaultDimensionModel;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class DimensionRepository extends RepositoryAbstract
{
    public $objectName = 'item';

    const GROUP_DIMENSION = 'e6824ae1-3a1c-4ccd-95e2-632cb16789f9';

    /**
     * @param  \BusinessCentral\Models\DefaultDimension  $defaultDimension
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function create(DefaultDimensionModel $defaultDimension)
    {

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.
            config('business-central.companyId').')/items('.
            $defaultDimension->parentId.')/defaultDimensions',
            $defaultDimension->toArray()
        );

        return $response;
    }

    /**
     * @param  \BusinessCentral\Models\DefaultDimension  $defaultDimension
     * @return \stdClass|null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function update(DefaultDimensionModel $defaultDimension)
    {

        $response = $this->client->patch(
            config('business-central.endpoint').'companies('.
            config('business-central.companyId').')/items('.
            $defaultDimension->parentId.')/defaultDimensions('.
            $defaultDimension->parentId.','.
            $defaultDimension->dimensionValueId.')'
        );

        return $response;
    }


}
