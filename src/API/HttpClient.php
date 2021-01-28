<?php

declare(strict_types=1);

namespace Daalder\BusinessCentral\API;

use Daalder\BusinessCentral\API\Repositories\CustomerRepository;
use Daalder\BusinessCentral\API\Repositories\DimensionValueRepository;
use Daalder\BusinessCentral\API\Repositories\ItemCategoryRepository;
use Daalder\BusinessCentral\API\Repositories\ItemRepository;
use Daalder\BusinessCentral\API\Repositories\PictureRepository;
use Daalder\BusinessCentral\API\Repositories\SalesOrderLineRepository;
use Daalder\BusinessCentral\API\Repositories\SalesOrderRepository;
use Daalder\BusinessCentral\API\Repositories\SalesQuoteLineRepository;
use Daalder\BusinessCentral\API\Repositories\SalesQuoteRepository;
use Daalder\BusinessCentral\API\Repositories\SubscriptionRepository;
use Daalder\BusinessCentral\API\Repositories\WarehouseShipmentRepository;
use Daalder\BusinessCentral\API\Traits\Utility\InstantiatorTrait;
use Daalder\BusinessCentral\API\Utilities\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Zendesk\API\Middleware\RetryHandler;

/**
 * Class HttpClient
 *
 * @package BusinessCentral\API
 *
 * @method ItemRepository item()
 * @method ItemCategoryRepository itemCategory()
 * @method SalesOrderRepository salesOrder()
 * @method SalesOrderLineRepository salesOrderLine()
 * @method SalesQuoteLineRepository salesQuoteLine()
 * @method CustomerRepository customer()
 * @method DimensionValueRepository dimensionValue()
 * @method SalesQuoteRepository salesQuote()
 */
class HttpClient
{
    use InstantiatorTrait;

    public Client $guzzle;
    protected string $apiUrl;
    protected string $hostname;
    protected string $scheme;
    protected Debug $debug;
    protected int $port;
    protected string $subdomain;
    protected Auth $auth;
    protected string $apiBasePath;
    /**
     * @var array $headers
     */
    private array $headers = [];

    /**
     * HttpClient constructor.
     *
     * @param        $subdomain
     * @param string $username
     * @param string $scheme
     * @param string $hostname
     * @param int $port
     * @param null $guzzle
     */
    public function __construct(
        $subdomain,
        string $username = '',
        string $scheme = 'https',
        string $hostname = '',
        int $port = 443,
        $guzzle = null
    ) {
        if (is_null($guzzle)) {
            $handler = HandlerStack::create();
            $handler->push(new RetryHandler([
                'retry_if' => static function ($retries, $request, $response, $e) {
                    return $e instanceof RequestException && strpos($e->getMessage(), 'ssl') !== false;
                },
            ]), 'retry_handler');
            $this->guzzle = new \GuzzleHttp\Client(compact('handler'));
        } else {
            $this->guzzle = $guzzle;
        }

        $this->subdomain = $subdomain;
        $this->hostname = $hostname;
        $this->scheme = $scheme;
        $this->port = $port;

//        if (empty($subdomain)) {
//            $this->apiUrl = "$scheme://$hostname:$port/";
//        } else {
//            $this->apiUrl = "$scheme://$subdomain.$hostname:$port/";
//        }
        $this->apiUrl = $hostname;

        $this->debug = new Debug();
    }

    /**
     * @return string[]
     */
    public static function getValidSubResources(): array
    {
        return [
            'item' => ItemRepository::class,
            'itemCategory' => ItemCategoryRepository::class,
            'salesOrder' => SalesOrderRepository::class,
            'customer' => CustomerRepository::class,
            'salesOrderLine' => SalesOrderLineRepository::class,
            'salesQuote' => SalesQuoteRepository::class,
            'salesQuoteLine' => SalesQuoteLineRepository::class,
            'warehouseShipment' => WarehouseShipmentRepository::class,
            'picture' => PictureRepository::class,
            'subscription' => SubscriptionRepository::class,
        ];
    }

    public function getAuth(): Auth
    {
        return $this->auth;
    }

    /**
     * @param $strategy
     * @param array $options
     */
    public function setAuth($strategy, array $options): void
    {
        $this->auth = new Auth($strategy, $options);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param  string  $key  The name of the header to set
     * @param  string  $value  The value to set in the header
     *
     * @internal param array $headers
     */
    public function setHeader(string $key, string $value): HttpClient
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Return the user agent string
     */
    public function getUserAgent(): string
    {
        return 'ZendeskAPI PHP ';
    }

    /**
     * Returns the supplied subdomain
     */
    public function getSubdomain(): string
    {
        return $this->subdomain;
    }

    /**
     * Returns the generated api URL
     */
    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Sets the api base path
     */
    public function setApiBasePath(string $apiBasePath): void
    {
        $this->apiBasePath = $apiBasePath;
    }

    /**
     * Returns the api base path
     */
    public function getApiBasePath(): string
    {
        return $this->apiBasePath;
    }

    /**
     * Set debug information as an object
     *
     * @param mixed $lastRequestHeaders
     * @param mixed $lastRequestBody
     * @param mixed $lastResponseCode
     * @param string $lastResponseHeaders
     * @param mixed $lastResponseError
     */
    public function setDebug(
        mixed $lastRequestHeaders,
        mixed $lastRequestBody,
        mixed $lastResponseCode,
        string $lastResponseHeaders,
        $lastResponseError
    ): void {
        $this->debug->lastRequestHeaders = $lastRequestHeaders;
        $this->debug->lastRequestBody = $lastRequestBody;
        $this->debug->lastResponseCode = $lastResponseCode;
        $this->debug->lastResponseHeaders = $lastResponseHeaders;
        $this->debug->lastResponseError = $lastResponseError;
    }

    /**
     * Returns debug information in an object
     */
    public function getDebug(): Debug
    {
        return $this->debug;
    }

    /**
     * @param $endpoint
     * @param array $postData
     * @param array $options
     * @return \stdClass|null
     * @throws Exceptions\ApiResponseException
     * @throws GuzzleException
     */
    public function post($endpoint, array $postData = [], array $options = []): ?\stdClass
    {
        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method' => 'POST',
        ]);

        return Http::send(
            $this,
            $endpoint,
            $extraOptions
        );
    }

    /**
     * @param $endpoint
     * @param array $postData
     * @param array $options
     * @return \stdClass|null
     * @throws Exceptions\ApiResponseException
     * @throws GuzzleException
     */
    public function patch($endpoint, array $postData = [], array $options = []): ?\stdClass
    {
        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method' => 'PATCH',
        ]);

        $this->setHeader('If-Match', '*');

        return Http::send(
            $this,
            $endpoint,
            $extraOptions
        );
    }

    /**
     * @param $endpoint
     * @param array $putData
     * @return \stdClass|null
     * @throws Exceptions\ApiResponseException
     * @throws GuzzleException
     */
    public function put($endpoint, array $putData = []): ?\stdClass
    {
        return Http::send(
            $this,
            $endpoint,
            ['postFields' => $putData, 'method' => 'PUT']
        );
    }

    /**
     * @param $endpoint
     * @return \stdClass|null
     * @throws Exceptions\ApiResponseException
     * @throws GuzzleException
     */
    public function delete($endpoint)
    {
        return Http::send(
            $this,
            $endpoint,
            ['method' => 'DELETE']
        );
    }

    /**
     * @param $endpoint
     * @param array $queryParams
     * @return \stdClass|null
     * @throws Exceptions\ApiResponseException
     * @throws GuzzleException
     */
    public function get($endpoint, array $queryParams = []): ?\stdClass
    {
//        $sideloads = $this->getSideload($queryParams);
//
//        if (is_array($sideloads)) {
//            $queryParams['include'] = implode(',', $sideloads);
//            unset($queryParams['sideload']);
//        }

        return Http::send(
            $this,
            $endpoint,
            ['queryParams' => $queryParams]
        );
    }
}
