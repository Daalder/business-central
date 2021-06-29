<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use Daalder\BusinessCentral\Models\DefaultDimension as DefaultDimensionModel;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class DimensionRepository extends RepositoryAbstract
{
    const GROUP_DIMENSION = 'e6824ae1-3a1c-4ccd-95e2-632cb16789f9';
    protected $objectName = 'item';

    public function create(DefaultDimensionModel $defaultDimension): ?\stdClass
    {
        return $this->client->post(
            config('business-central.endpoint').'companies('.
            config('business-central.companyId').')/items('.
            $defaultDimension->parentId.')/defaultDimensions',
            $defaultDimension->toArray()
        );
    }

    public function update(DefaultDimensionModel $defaultDimension): ?\stdClass
    {
        return $this->client->patch(
            config('business-central.endpoint').'companies('.
            config('business-central.companyId').')/items('.
            $defaultDimension->parentId.')/defaultDimensions('.
            $defaultDimension->parentId.','.
            $defaultDimension->dimensionValueId.')'
        );
    }
}