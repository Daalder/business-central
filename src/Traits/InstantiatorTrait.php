<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Traits;

use Daalder\BusinessCentral\API\HttpClient;
use Daalder\BusinessCentral\Repositories\RepositoryAbstract;
use Daalder\BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Support\Facades\App;

/**
 * The Instantiator trait which has the magic methods for instantiating Resources
 *
 * @package Zendesk\API
 */
trait InstantiatorTrait
{
    /**
     * Generic method to object getter. Since all objects are protected, this method
     * exposes a getter function with the same name as the protected variable, for example
     * $client->tickets can be referenced by $client->tickets()
     *
     * @param $name
     * @param $arguments
     *
     * @throws \Exception
     */
    public function __call($name, $arguments): ChainedParametersTrait
    {
        if ((array_key_exists($name, $validSubResources = $this::getValidSubResources()))) {
            $reference = App::make(ReferenceRepository::class);
            $className = $validSubResources[$name];
            //dd($this->client);
            $client = $this instanceof HttpClient ? $this : $this->client;
            $class = new $className($client, $reference);
        } else {
            throw new \Exception("No method called ${name} available in ".self::class);
        }

        $chainedParams = $this instanceof RepositoryAbstract ? $this->getChainedParameters() : [];

        if (isset($arguments[0]) && ($arguments[0] !== null)) {
            $chainedParams = array_merge($chainedParams, [get_class($class) => $arguments[0]]);
        }

        return $class->setChainedParameters($chainedParams);
    }
}
