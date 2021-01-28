<?php

namespace BusinessCentral\API\Repositories;


use BusinessCentral\API\HttpClient;
use BusinessCentral\Models\ReferenceModel;
use BusinessCentral\Repositories\ReferenceRepository;
use Zendesk\API\Traits\Utility\ChainedParametersTrait;

/**
 * Class ResourceAbstract
 *
 * @package BusinessCentral\API\Resources
 */
abstract class RepositoryAbstract
{
    use ChainedParametersTrait;

    /**
     * @var \BusinessCentral\API\HttpClient
     */
    protected $client;
    /**
     * @var \BusinessCentral\Repositories\ReferenceRepository
     */
    protected $referenceRepository;

    /**
     * ResourceAbstract constructor.
     *
     * @param  \BusinessCentral\API\HttpClient  $client
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     */
    public function __construct(HttpClient $client, ReferenceRepository $referenceRepository)
    {
        $this->client              = $client;
        $this->referenceRepository = $referenceRepository;
    }

    /**
     * @param  \BusinessCentral\Models\ReferenceModel  $model
     */
    public function storeReference(ReferenceModel $model)
    {
        $this->referenceRepository->storeReference($model);
    }
}
