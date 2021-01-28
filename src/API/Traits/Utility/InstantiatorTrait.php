<?php

namespace BusinessCentral\API\Traits\Utility;


use BusinessCentral\API\HttpClient;
use BusinessCentral\API\Repositories\RepositoryAbstract;
use BusinessCentral\Repositories\ReferenceRepository;
use Illuminate\Support\Facades\App;

/**
 * The Instantiator trait which has the magic methods for instantiating Resources
 *
 * @package Zendesk\API
 *
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
     * @return ChainedParametersTrait
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if ((array_key_exists($name, $validSubResources = $this::getValidSubResources()))) {
            $reference = App::make(ReferenceRepository::class);
            $className = $validSubResources[$name];
            //dd($this->client);
            $client = ($this instanceof HttpClient) ? $this : $this->client;
            $class  = new $className($client, $reference);
        } else {
            throw new \Exception("No method called $name available in ".__CLASS__);
        }

        $chainedParams = ($this instanceof RepositoryAbstract) ? $this->getChainedParameters() : [];

        if ((isset($arguments[0])) && ($arguments[0] != null)) {
            $chainedParams = array_merge($chainedParams, [get_class($class) => $arguments[0]]);
        }

        $class = $class->setChainedParameters($chainedParams);

        return $class;
    }
}
