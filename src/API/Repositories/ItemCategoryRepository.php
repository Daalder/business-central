<?php

namespace BusinessCentral\API\Repositories;


use BusinessCentral\API\Resources\ItemCategory;
use BusinessCentral\Commands\PullFromBusinessCentral;
use BusinessCentral\Models\SetBusinessCentral;
use Pionect\Backoffice\Models\ProductAttribute\Set;

/**
 * Class Product
 *
 * @package BusinessCentral\API\Resources
 */
class ItemCategoryRepository extends RepositoryAbstract
{
    public $objectName = 'itemCategories';

    /**
     * @param  \Pionect\Backoffice\Models\ProductAttribute\Set  $set
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function create(Set $set)
    {
        $resource = new ItemCategory($set);

        $response = $this->client->post(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/itemCategories', $resource->resolve()
        );

        $this->storeReference(new SetBusinessCentral([
            'productattributeset_id' => $set->id,
            'business_central_id'    => $response->id
        ]));

        return $response;
    }

    /**
     * @param  array  $params
     * @param       $ref
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function update(array $params, $ref)
    {
        return $this->client->patch(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/itemCategories('.$ref.')', $params
        );
    }

    /**
     * @param $ref
     * @return null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function delete($ref)
    {
        return $this->client->delete(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/itemCategories('.$ref.')'
        );
    }


    /**
     * @param  \BusinessCentral\Commands\PullFromBusinessCentral  $command
     * @param  int  $top
     * @param  int  $skip
     * @return \stdClass|null
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function pullReferences(PullFromBusinessCentral $command, $top = 20000, $skip = 0)
    {

        $response = $this->client->get(
            config('business-central.endpoint').'companies('.config('business-central.companyId').')/itemCategories?$top='.$top.'&$skip='.$skip
        );

        foreach ($response->value as $item) {
            $set = Set::where('id', $item->code)->withTrashed()->orderBy('id', 'desc')->first();
            if ($set) {
                if (!$this->referenceRepository->getReference(new SetBusinessCentral(['productattributeset_id' => $set->id]))) {
                    $this->storeReference(new SetBusinessCentral([
                        'productattributeset_id' => $set->id,
                        'business_central_id'    => $item->id
                    ]));
                }
            } else {
                $command->error('Set not found: '.$item->code);
            }
        }

        return $response;
    }
}
