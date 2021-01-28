<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Models\ReferenceModel;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Zendesk\API\Traits\Utility\ChainedParametersTrait;

/**
 * Class ResourceAbstract
 *
 * @package BusinessCentral\API\Resources
 */
abstract class RepositoryAbstract
{
    use ChainedParametersTrait;

    protected \BusinessCentral\API\HttpClient $client;
    protected \BusinessCentral\Repositories\ReferenceRepository $referenceRepository;

    /**
     * ResourceAbstract constructor.
     *
     * @param  \BusinessCentral\API\HttpClient  $client
     * @param  \BusinessCentral\Repositories\ReferenceRepository  $referenceRepository
     */
    public function __construct(HttpClient $client, ReferenceRepository $referenceRepository)
    {
        $this->client = $client;
        $this->referenceRepository = $referenceRepository;
    }

    /**
     * @param  \BusinessCentral\Models\ReferenceModel  $model
     */
    public function storeReference(ReferenceModel $model): void
    {
        $this->referenceRepository->storeReference($model);
    }
}
