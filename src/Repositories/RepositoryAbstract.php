<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Traits\ChainedParametersTrait;
use Daalder\BusinessCentral\Models\ReferenceModel;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;

/**
 * Class RepositoryAbstract
 * @package Daalder\BusinessCentral\API\Repositories
 */
abstract class RepositoryAbstract
{
    use ChainedParametersTrait;

    protected HttpClient $client;
    protected ReferenceRepository $referenceRepository;

    /**
     * RepositoryAbstract constructor.
     * @param HttpClient $client
     * @param ReferenceRepository $referenceRepository
     */
    public function __construct(HttpClient $client, ReferenceRepository $referenceRepository)
    {
        $this->client = $client;
        $this->referenceRepository = $referenceRepository;
    }

    /**
     * @param ReferenceModel $model
     */
    public function storeReference(ReferenceModel $model): void
    {
        $this->referenceRepository->storeReference($model);
    }
}
