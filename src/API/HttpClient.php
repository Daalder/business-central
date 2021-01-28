<?php

namespace BusinessCentral\API;

use BusinessCentral\API\Repositories\CustomerRepository;
use BusinessCentral\API\Repositories\DimensionValueRepository;
use BusinessCentral\API\Repositories\ItemCategoryRepository;
use BusinessCentral\API\Repositories\ItemRepository;
use BusinessCentral\API\Repositories\PictureRepository;
use BusinessCentral\API\Repositories\SalesOrderLineRepository;
use BusinessCentral\API\Repositories\SalesOrderRepository;
use BusinessCentral\API\Repositories\SalesQuoteLineRepository;
use BusinessCentral\API\Repositories\SalesQuoteRepository;
use BusinessCentral\API\Repositories\SubscriptionRepository;
use BusinessCentral\API\Repositories\WarehouseShipmentRepository;
use BusinessCentral\API\Traits\Utility\InstantiatorTrait;
use BusinessCentral\API\Utilities\Auth;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Zendesk\API\Middleware\RetryHandler;


/**
 * Class HttpClient
 *
 * @package BusinessCentral\API
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


    public $guzzle;
    /**
     * @var array $headers
     */
    private $headers = [];
    protected $apiUrl;
    protected $hostname;
    protected $scheme;
    protected $debug;
    protected $port;
    protected $subdomain;
    protected $auth;
    protected $apiBasePath;

    /**
     * HttpClient constructor.
     *
     * @param        $subdomain
     * @param  string  $username
     * @param  string  $scheme
     * @param  string  $hostname
     * @param  int  $port
     * @param  null  $guzzle
     */
    public function __construct(
        $subdomain,
        $username = '',
        $scheme = "https",
        $hostname = "",
        $port = 443,
        $guzzle = null
    ) {
        if (is_null($guzzle)) {
            $handler = HandlerStack::create();
            $handler->push(new RetryHandler([
                'retry_if' => function ($retries, $request, $response, $e) {
                    return $e instanceof RequestException && strpos($e->getMessage(), 'ssl') !== false;
                }
            ]), 'retry_handler');
            $this->guzzle = new \GuzzleHttp\Client(compact('handler'));
        } else {
            $this->guzzle = $guzzle;
        }

        $this->subdomain = $subdomain;
        $this->hostname  = $hostname;
        $this->scheme    = $scheme;
        $this->port      = $port;

//        if (empty($subdomain)) {
//            $this->apiUrl = "$scheme://$hostname:$port/";
//        } else {
//            $this->apiUrl = "$scheme://$subdomain.$hostname:$port/";
//        }
        $this->apiUrl = $hostname;

        $this->debug = new Debug();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getValidSubResources()
    {
        return [
            'item'              => ItemRepository::class,
            'itemCategory'      => ItemCategoryRepository::class,
            'salesOrder'        => SalesOrderRepository::class,
            'customer'          => CustomerRepository::class,
            'salesOrderLine'    => SalesOrderLineRepository::class,
            'salesQuote'        => SalesQuoteRepository::class,
            'salesQuoteLine'    => SalesQuoteLineRepository::class,
            'warehouseShipment' => WarehouseShipmentRepository::class,
            'picture'           => PictureRepository::class,
            'subscription'      => SubscriptionRepository::class
        ];
    }

    /**
     * @return Auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param       $strategy
     * @param  array  $options
     * @throws \Zendesk\API\Exceptions\AuthException
     */
    public function setAuth($strategy, array $options)
    {
        $this->auth = new Auth($strategy, $options);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param  string  $key  The name of the header to set
     * @param  string  $value  The value to set in the header
     * @return HttpClient
     * @internal param array $headers
     *
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * Return the user agent string
     *
     * @return string
     */
    public function getUserAgent()
    {
        return 'ZendeskAPI PHP ';
    }

    /**
     * Returns the supplied subdomain
     *
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * Returns the generated api URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the api base path
     *
     * @param  string  $apiBasePath
     */
    public function setApiBasePath($apiBasePath)
    {
        $this->apiBasePath = $apiBasePath;
    }

    /**
     * Returns the api base path
     *
     * @return string
     */
    public function getApiBasePath()
    {
        return $this->apiBasePath;
    }

    /**
     * Set debug information as an object
     *
     * @param  mixed  $lastRequestHeaders
     * @param  mixed  $lastRequestBody
     * @param  mixed  $lastResponseCode
     * @param  string  $lastResponseHeaders
     * @param  mixed  $lastResponseError
     */
    public function setDebug(
        $lastRequestHeaders,
        $lastRequestBody,
        $lastResponseCode,
        $lastResponseHeaders,
        $lastResponseError
    ) {
        $this->debug->lastRequestHeaders  = $lastRequestHeaders;
        $this->debug->lastRequestBody     = $lastRequestBody;
        $this->debug->lastResponseCode    = $lastResponseCode;
        $this->debug->lastResponseHeaders = $lastResponseHeaders;
        $this->debug->lastResponseError   = $lastResponseError;
    }

    /**
     * Returns debug information in an object
     *
     * @return Debug
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * This is a helper method to do a post request.
     *
     * @param       $endpoint
     * @param  array  $postData
     *
     * @param  array  $options
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\AuthException
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     */
    public function post($endpoint, $postData = [], $options = [])
    {
        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method'     => 'POST'
        ]);

        $response = Http::send(
            $this,
            $endpoint,
            $extraOptions
        );

        return $response;
    }

    /**
     * This is a helper method to do a post request.
     *
     * @param       $endpoint
     * @param  array  $postData
     *
     * @param  array  $options
     * @return null|\stdClass
     * @throws \Zendesk\API\Exceptions\AuthException
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     */
    public function patch($endpoint, $postData = [], $options = [])
    {
        $extraOptions = array_merge($options, [
            'postFields' => $postData,
            'method'     => 'PATCH'
        ]);

        $this->setHeader('If-Match', '*');

        $response = Http::send(
            $this,
            $endpoint,
            $extraOptions
        );

        return $response;
    }


    /**
     * This is a helper method to do a put request.
     *
     * @param       $endpoint
     * @param  array  $putData
     *
     * @return \stdClass | null
     * @throws \Zendesk\API\Exceptions\AuthException
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     */
    public function put($endpoint, $putData = [])
    {
        $response = Http::send(
            $this,
            $endpoint,
            ['postFields' => $putData, 'method' => 'PUT']
        );

        return $response;
    }

    /**
     * This is a helper method to do a delete request.
     *
     * @param $endpoint
     *
     * @return null
     * @throws \Zendesk\API\Exceptions\AuthException
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     */
    public function delete($endpoint)
    {
        $response = Http::send(
            $this,
            $endpoint,
            ['method' => 'DELETE']
        );

        return $response;
    }

    /**
     * This is a helper method to do a get request.
     *
     * @param       $endpoint
     * @param  array  $queryParams
     *
     * @return \stdClass | null
     * @throws \Zendesk\API\Exceptions\AuthException
     * @throws \Zendesk\API\Exceptions\ApiResponseException
     */
    public function get($endpoint, $queryParams = [])
    {
//        $sideloads = $this->getSideload($queryParams);
//
//        if (is_array($sideloads)) {
//            $queryParams['include'] = implode(',', $sideloads);
//            unset($queryParams['sideload']);
//        }

        $response = Http::send(
            $this,
            $endpoint,
            ['queryParams' => $queryParams]
        );

        return $response;
    }
}
