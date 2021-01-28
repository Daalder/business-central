<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API\Repositories;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\API\Traits\Utility\ChainedParametersTrait;
use Daalder\BusinessCentral\Models\ReferenceModel;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;

/**
 * Class ResourceAbstract
 *
 * @package BusinessCentral\API\Resources
 */
abstract class RepositoryAbstract
{
    use ChainedParametersTrait;

    protected HttpClient $client;
    protected ReferenceRepository $referenceRepository;

    /**
     * RepositoryAbstract constructor.
     */
    public function __construct(HttpClient $client, ReferenceRepository $referenceRepository)
    {
        $this->client = $client;
        $this->referenceRepository = $referenceRepository;
    }

    public function storeReference(ReferenceModel $model): void
    {
        $this->referenceRepository->storeReference($model);
    }
}
