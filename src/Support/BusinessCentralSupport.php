<?php

namespace BusinessCentral\Support;

use BusinessCentral\API\Services\NamespaceTranslations;

class BusinessCentralSupport
{
    /**
     * @param string $subscriptionId
     * @return string
     */
    public static function getRepositoryFromSubscriptionId(string $subscriptionId): string
    {
        $regex = '/\/(.*)\/\([a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}\)$';
        preg_match($regex, $subscriptionId, $matches);

        if(isset($matches[1]) && class_exists($matches[1])) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param string $resourceUrl
     * @return string
     */
    public static function getBusinessCentralIdFromResourceUrl(string $resourceUrl): string
    {
        $regex = '/\([a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12}\)$';
        preg_match($regex, $resourceUrl, $matches);

        if(isset($matches[1]) && class_exists($matches[1])) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param string $respositoryClassName
     * @return string
     */
    public static function getSubscriptionIdFromRepository(string $respositoryClassName): string
    {
        return array_flip(NamespaceTranslations::$NAMESPACES)[$respositoryClassName];
    }
}