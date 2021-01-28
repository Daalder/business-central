<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\Translators;

use Daalder\BusinessCentral\Models\OrderBusinessCentral;
use Daalder\BusinessCentral\Models\ProductBusinessCentral;
use Daalder\BusinessCentral\Translators\Contracts\TranslatorContract;
use Daalder\BusinessCentral\Translators\Resource\OrderTranslator;
use Daalder\BusinessCentral\Translators\Resource\ProductTranslator;
use Exception;

class TranslatorFactory
{
    /**
     * Mapping resource to translator.
     *
     * @var array
     */
    protected static array $_BUSINESS_CENTRAL_TRANSLATORS = [
        'items' => ProductTranslator::class,
        'order' => OrderTranslator::class,
    ];

    /**
     * Mapping resource to translator.
     *
     * @var array
     */
    protected static array $_BACKOFFICE_TRANSLATORS = [
        ProductBusinessCentral::class => ProductTranslator::class,
        OrderBusinessCentral::class => OrderTranslator::class,
    ];

    /**
     * @param $translationBase
     * @param array $payload
     *
     * @throws Exception
     */
    public static function make($translationBase, array $payload = []): TranslatorContract
    {
        try {
            $resourceProperties = self::parseBusinessCentralResourceUrl($translationBase);
            return self::makeBusinessCentralTranslator($resourceProperties, $payload);
        } catch (Exception $e) {
        }

        try {
            $className = get_class($translationBase);
            if (in_array($className, array_keys(self::$_BACKOFFICE_TRANSLATORS))) {
                return self::makeBackOfficeTranslator($translationBase, $payload);
            }
        } catch (Exception $e) {
        }

        throw new Exception('Cannot make a translator.');
    }

    /**
     * Make translator for BusinessCentral API to BackOffice translations.
     *
     * @param array $payload
     *
     * @throws Exception
     */
    public static function makeBusinessCentralTranslator(string $resourceUrl, array $payload = []): TranslatorContract
    {
        $resourceProperties = self::parseBusinessCentralResourceUrl($resourceUrl);
        return (new self::$_BUSINESS_CENTRAL_TRANSLATORS[$resourceProperties['resource']]())->setPayload($payload)->fromBusinessCentral();
    }

    /**
     * Alias for makeBusinessCentralTranslator.
     *
     * @throws Exception
     */
    public static function businessCentral(string $resourceUrl): TranslatorContract
    {
        return self::makeBusinessCentralTranslator($resourceUrl);
    }

    /**
     * Make translator for BackOffice to BusinessCentral translations.
     */
    public static function makeBackOfficeTranslator($resource): TranslatorContract
    {
        $resourceClassName = get_class($resource);
        return (new self::$_BACKOFFICE_TRANSLATORS[$resourceClassName]())()->fromBackOffice($resource);
    }

    /**
     * Alias for makeBackOfficeTranslator.
     */
    public static function backOffice(): TranslatorContract
    {
        return self::makeBackOfficeTranslator();
    }

    /**
     * Parse resource URL and return result as an array with 'resource' and 'id' elements.
     *
     * @see https://docs.microsoft.com/en-us/dynamics-nav/api-reference/v1.0/dynamics_subscriptions
     * @see api/v1.0/companies(b18aed47-c385-49d2-b954-dbdf8ad71780)/items(26814998-936a-401c-81c1-0e848a64971d)
     *
     * @return array
     *
     * @throws Exception
     */
    protected static function parseBusinessCentralResourceUrl(string $resourceUrl): array
    {
        $regex = '/\/(?<resource>[a-z\-]+)\((?<id>[a-f0-9]{8}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{4}\-[a-f0-9]{12})\)/';
        if (preg_match_all($regex, $resourceUrl, $matches)) {
            return [
                'resource' => end($matches['resource']),
                'business_central_id' => end($matches['id']),
            ];
        }

        throw new Exception('Not a valid Business Central API resource URL');
    }
}
