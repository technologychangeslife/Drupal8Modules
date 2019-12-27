<?php

namespace Drupal\tmgmt_contentapi\Swagger\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Drupal\tmgmt_contentapi\Swagger\Client\ApiException;
use Drupal\tmgmt_contentapi\Swagger\Client\Configuration;
use Drupal\tmgmt_contentapi\Swagger\Client\HeaderSelector;
use Drupal\tmgmt_contentapi\Swagger\Client\ObjectSerializer;

/**
 * StatusUpdateApi Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class StatusUpdateApi {
  /**
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * @var \Drupal\tmgmt_contentapi\Swagger\Client\Configuration
   */
  protected $config;

  /**
   * @param \GuzzleHttp\ClientInterface $client
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Configuration $config
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\HeaderSelector $selector
   */
  public function __construct(
        ClientInterface $client = NULL,
        Configuration $config = NULL,
        HeaderSelector $selector = NULL
    ) {
    $this->client = $client ?: new Client();
    $this->config = $config ?: new Configuration();
    $this->headerSelector = $selector ?: new HeaderSelector();
  }

  /**
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Configuration
   */
  public function getConfig() {
    return $this->config;
  }

  /**
   * Operation jobsJobIdStatusupdatesGet.
   *
   * Get all unacknowledged status updates for this job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]
   */
  public function jobsJobIdStatusupdatesGet($authorization, $job_id) {
    list($response) = $this->jobsJobIdStatusupdatesGetWithHttpInfo($authorization, $job_id);
    return $response;
  }

  /**
   * Operation jobsJobIdStatusupdatesGetWithHttpInfo.
   *
   * Get all unacknowledged status updates for this job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[], HTTP status code, HTTP response headers (array of strings)
   */
  public function jobsJobIdStatusupdatesGetWithHttpInfo($authorization, $job_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]';
    $request = $this->jobsJobIdStatusupdatesGetRequest($authorization, $job_id);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 200:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 401:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation jobsJobIdStatusupdatesGetAsync.
   *
   * Get all unacknowledged status updates for this job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdStatusupdatesGetAsync($authorization, $job_id) {
    return $this->jobsJobIdStatusupdatesGetAsyncWithHttpInfo($authorization, $job_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation jobsJobIdStatusupdatesGetAsyncWithHttpInfo.
   *
   * Get all unacknowledged status updates for this job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdStatusupdatesGetAsyncWithHttpInfo($authorization, $job_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]';
    $request = $this->jobsJobIdStatusupdatesGetRequest($authorization, $job_id);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'jobsJobIdStatusupdatesGet'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function jobsJobIdStatusupdatesGetRequest($authorization, $job_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling jobsJobIdStatusupdatesGet'
      );
    }
    // Verify the required parameter 'job_id' is set.
    if ($job_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $job_id when calling jobsJobIdStatusupdatesGet'
      );
    }

    $resourcePath = '/jobs/{jobId}/statusupdates';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Header params.
    if ($authorization !== NULL) {
      $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
    }

    // Path params.
    if ($job_id !== NULL) {
      $resourcePath = str_replace(
        '{' . 'jobId' . '}',
        ObjectSerializer::toPathValue($job_id),
        $resourcePath
      );
    }

    // Body params.
    $_tempBody = NULL;

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
        );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('Authorization');
    if ($apiKey !== NULL) {
      $headers['Authorization'] = $apiKey;
    }
    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('x-api-key');
    if ($apiKey !== NULL) {
      $headers['x-api-key'] = $apiKey;
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'GET',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Operation statusupdatesGet.
   *
   * Get all unacknowledged status updates.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]
   */
  public function statusupdatesGet($authorization) {
    list($response) = $this->statusupdatesGetWithHttpInfo($authorization);
    return $response;
  }

  /**
   * Operation statusupdatesGetWithHttpInfo.
   *
   * Get all unacknowledged status updates.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[], HTTP status code, HTTP response headers (array of strings)
   */
  public function statusupdatesGetWithHttpInfo($authorization) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]';
    $request = $this->statusupdatesGetRequest($authorization);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 200:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 401:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation statusupdatesGetAsync.
   *
   * Get all unacknowledged status updates.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesGetAsync($authorization) {
    return $this->statusupdatesGetAsyncWithHttpInfo($authorization)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation statusupdatesGetAsyncWithHttpInfo.
   *
   * Get all unacknowledged status updates.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesGetAsyncWithHttpInfo($authorization) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate[]';
    $request = $this->statusupdatesGetRequest($authorization);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'statusupdatesGet'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function statusupdatesGetRequest($authorization) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling statusupdatesGet'
      );
    }

    $resourcePath = '/statusupdates';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Header params.
    if ($authorization !== NULL) {
      $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
    }

    // Body params.
    $_tempBody = NULL;

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
        );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('Authorization');
    if ($apiKey !== NULL) {
      $headers['Authorization'] = $apiKey;
    }
    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('x-api-key');
    if ($apiKey !== NULL) {
      $headers['x-api-key'] = $apiKey;
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'GET',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Operation statusupdatesListenersGet.
   *
   * Query all Listeners.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener[]
   */
  public function statusupdatesListenersGet($authorization) {
    list($response) = $this->statusupdatesListenersGetWithHttpInfo($authorization);
    return $response;
  }

  /**
   * Operation statusupdatesListenersGetWithHttpInfo.
   *
   * Query all Listeners.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener[], HTTP status code, HTTP response headers (array of strings)
   */
  public function statusupdatesListenersGetWithHttpInfo($authorization) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener[]';
    $request = $this->statusupdatesListenersGetRequest($authorization);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 200:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener[]',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 401:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation statusupdatesListenersGetAsync.
   *
   * Query all Listeners.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersGetAsync($authorization) {
    return $this->statusupdatesListenersGetAsyncWithHttpInfo($authorization)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation statusupdatesListenersGetAsyncWithHttpInfo.
   *
   * Query all Listeners.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersGetAsyncWithHttpInfo($authorization) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener[]';
    $request = $this->statusupdatesListenersGetRequest($authorization);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'statusupdatesListenersGet'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function statusupdatesListenersGetRequest($authorization) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling statusupdatesListenersGet'
      );
    }

    $resourcePath = '/statusupdates/listeners';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Header params.
    if ($authorization !== NULL) {
      $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
    }

    // Body params.
    $_tempBody = NULL;

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
        );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('Authorization');
    if ($apiKey !== NULL) {
      $headers['Authorization'] = $apiKey;
    }
    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('x-api-key');
    if ($apiKey !== NULL) {
      $headers['x-api-key'] = $apiKey;
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'GET',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Operation statusupdatesListenersListenerIdDelete.
   *
   * Delete a Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the specified Listener. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener
   */
  public function statusupdatesListenersListenerIdDelete($authorization, $listener_id) {
    list($response) = $this->statusupdatesListenersListenerIdDeleteWithHttpInfo($authorization, $listener_id);
    return $response;
  }

  /**
   * Operation statusupdatesListenersListenerIdDeleteWithHttpInfo.
   *
   * Delete a Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the specified Listener. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener, HTTP status code, HTTP response headers (array of strings)
   */
  public function statusupdatesListenersListenerIdDeleteWithHttpInfo($authorization, $listener_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener';
    $request = $this->statusupdatesListenersListenerIdDeleteRequest($authorization, $listener_id);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 200:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 401:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 404:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation statusupdatesListenersListenerIdDeleteAsync.
   *
   * Delete a Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the specified Listener. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersListenerIdDeleteAsync($authorization, $listener_id) {
    return $this->statusupdatesListenersListenerIdDeleteAsyncWithHttpInfo($authorization, $listener_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation statusupdatesListenersListenerIdDeleteAsyncWithHttpInfo.
   *
   * Delete a Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the specified Listener. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersListenerIdDeleteAsyncWithHttpInfo($authorization, $listener_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener';
    $request = $this->statusupdatesListenersListenerIdDeleteRequest($authorization, $listener_id);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'statusupdatesListenersListenerIdDelete'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the specified Listener. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function statusupdatesListenersListenerIdDeleteRequest($authorization, $listener_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling statusupdatesListenersListenerIdDelete'
      );
    }
    // Verify the required parameter 'listener_id' is set.
    if ($listener_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $listener_id when calling statusupdatesListenersListenerIdDelete'
      );
    }

    $resourcePath = '/statusupdates/listeners/{listenerId}';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Header params.
    if ($authorization !== NULL) {
      $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
    }

    // Path params.
    if ($listener_id !== NULL) {
      $resourcePath = str_replace(
        '{' . 'listenerId' . '}',
        ObjectSerializer::toPathValue($listener_id),
        $resourcePath
      );
    }

    // Body params.
    $_tempBody = NULL;

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
        );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('Authorization');
    if ($apiKey !== NULL) {
      $headers['Authorization'] = $apiKey;
    }
    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('x-api-key');
    if ($apiKey !== NULL) {
      $headers['x-api-key'] = $apiKey;
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'DELETE',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Operation statusupdatesListenersListenerIdGet.
   *
   * Query listener information.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the Listener being queried. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener
   */
  public function statusupdatesListenersListenerIdGet($authorization, $listener_id) {
    list($response) = $this->statusupdatesListenersListenerIdGetWithHttpInfo($authorization, $listener_id);
    return $response;
  }

  /**
   * Operation statusupdatesListenersListenerIdGetWithHttpInfo.
   *
   * Query listener information.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the Listener being queried. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener, HTTP status code, HTTP response headers (array of strings)
   */
  public function statusupdatesListenersListenerIdGetWithHttpInfo($authorization, $listener_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener';
    $request = $this->statusupdatesListenersListenerIdGetRequest($authorization, $listener_id);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 200:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 401:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 404:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation statusupdatesListenersListenerIdGetAsync.
   *
   * Query listener information.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the Listener being queried. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersListenerIdGetAsync($authorization, $listener_id) {
    return $this->statusupdatesListenersListenerIdGetAsyncWithHttpInfo($authorization, $listener_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation statusupdatesListenersListenerIdGetAsyncWithHttpInfo.
   *
   * Query listener information.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the Listener being queried. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersListenerIdGetAsyncWithHttpInfo($authorization, $listener_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener';
    $request = $this->statusupdatesListenersListenerIdGetRequest($authorization, $listener_id);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'statusupdatesListenersListenerIdGet'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $listener_id
   *   The ID of the Listener being queried. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function statusupdatesListenersListenerIdGetRequest($authorization, $listener_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling statusupdatesListenersListenerIdGet'
      );
    }
    // Verify the required parameter 'listener_id' is set.
    if ($listener_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $listener_id when calling statusupdatesListenersListenerIdGet'
      );
    }

    $resourcePath = '/statusupdates/listeners/{listenerId}';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Header params.
    if ($authorization !== NULL) {
      $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
    }

    // Path params.
    if ($listener_id !== NULL) {
      $resourcePath = str_replace(
        '{' . 'listenerId' . '}',
        ObjectSerializer::toPathValue($listener_id),
        $resourcePath
      );
    }

    // Body params.
    $_tempBody = NULL;

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
        );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('Authorization');
    if ($apiKey !== NULL) {
      $headers['Authorization'] = $apiKey;
    }
    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('x-api-key');
    if ($apiKey !== NULL) {
      $headers['x-api-key'] = $apiKey;
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'GET',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Operation statusupdatesListenersPost.
   *
   * Create a new Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateListener $body
   *   Created Listener object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener
   */
  public function statusupdatesListenersPost($authorization, $body) {
    list($response) = $this->statusupdatesListenersPostWithHttpInfo($authorization, $body);
    return $response;
  }

  /**
   * Operation statusupdatesListenersPostWithHttpInfo.
   *
   * Create a new Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateListener $body
   *   Created Listener object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener, HTTP status code, HTTP response headers (array of strings)
   */
  public function statusupdatesListenersPostWithHttpInfo($authorization, $body) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener';
    $request = $this->statusupdatesListenersPostRequest($authorization, $body);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 201:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 401:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation statusupdatesListenersPostAsync.
   *
   * Create a new Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateListener $body
   *   Created Listener object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersPostAsync($authorization, $body) {
    return $this->statusupdatesListenersPostAsyncWithHttpInfo($authorization, $body)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation statusupdatesListenersPostAsyncWithHttpInfo.
   *
   * Create a new Listener.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateListener $body
   *   Created Listener object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesListenersPostAsyncWithHttpInfo($authorization, $body) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Listener';
    $request = $this->statusupdatesListenersPostRequest($authorization, $body);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'statusupdatesListenersPost'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateListener $body
   *   Created Listener object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function statusupdatesListenersPostRequest($authorization, $body) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling statusupdatesListenersPost'
      );
    }
    // Verify the required parameter 'body' is set.
    if ($body === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $body when calling statusupdatesListenersPost'
      );
    }

    $resourcePath = '/statusupdates/listeners';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Header params.
    if ($authorization !== NULL) {
      $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
    }

    // Body params.
    $_tempBody = NULL;
    if (isset($body)) {
      $_tempBody = $body;
    }

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
      );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('Authorization');
    if ($apiKey !== NULL) {
      $headers['Authorization'] = $apiKey;
    }
    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('x-api-key');
    if ($apiKey !== NULL) {
      $headers['x-api-key'] = $apiKey;
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'POST',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Operation statusupdatesUpdateIdAcknowledgePut.
   *
   * Acknowledge a status update.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $update_id
   *   The ID of the status update being acknowledged. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate
   */
  public function statusupdatesUpdateIdAcknowledgePut($authorization, $update_id) {
    list($response) = $this->statusupdatesUpdateIdAcknowledgePutWithHttpInfo($authorization, $update_id);
    return $response;
  }

  /**
   * Operation statusupdatesUpdateIdAcknowledgePutWithHttpInfo.
   *
   * Acknowledge a status update.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $update_id
   *   The ID of the status update being acknowledged. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate, HTTP status code, HTTP response headers (array of strings)
   */
  public function statusupdatesUpdateIdAcknowledgePutWithHttpInfo($authorization, $update_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate';
    $request = $this->statusupdatesUpdateIdAcknowledgePutRequest($authorization, $update_id);

    try {
      $options = $this->createHttpClientOption();
      try {
        $response = $this->client->send($request, $options);
      }
      catch (RequestException $e) {
        throw new ApiException(
        "[{$e->getCode()}] {$e->getMessage()}",
        $e->getCode(),
        $e->getResponse() ? $e->getResponse()->getHeaders() : NULL,
        $e->getResponse() ? $e->getResponse()->getBody()->getContents() : NULL
          );
      }

      $statusCode = $response->getStatusCode();

      if ($statusCode < 200 || $statusCode > 299) {
        throw new ApiException(
        sprintf(
            '[%d] Error connecting to the API (%s)',
            $statusCode,
            $request->getUri()
        ),
        $statusCode,
        $response->getHeaders(),
        $response->getBody()
        );
      }

      $responseBody = $response->getBody();
      if ($returnType === '\SplFileObject') {
        // Stream goes to serializer.
        $content = $responseBody;
      }
      else {
        $content = $responseBody->getContents();
        if ($returnType !== 'string') {
          $content = json_decode($content);
        }
      }

      return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
    ];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
        case 200:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 400:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 401:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 404:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;

        case 500:
          $data = ObjectSerializer::deserialize(
          $e->getResponseBody(),
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Error',
          $e->getResponseHeaders()
          );
          $e->setResponseObject($data);
          break;
      }
      throw $e;
    }
  }

  /**
   * Operation statusupdatesUpdateIdAcknowledgePutAsync.
   *
   * Acknowledge a status update.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $update_id
   *   The ID of the status update being acknowledged. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesUpdateIdAcknowledgePutAsync($authorization, $update_id) {
    return $this->statusupdatesUpdateIdAcknowledgePutAsyncWithHttpInfo($authorization, $update_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation statusupdatesUpdateIdAcknowledgePutAsyncWithHttpInfo.
   *
   * Acknowledge a status update.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $update_id
   *   The ID of the status update being acknowledged. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function statusupdatesUpdateIdAcknowledgePutAsyncWithHttpInfo($authorization, $update_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\StatusUpdate';
    $request = $this->statusupdatesUpdateIdAcknowledgePutRequest($authorization, $update_id);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                $responseBody = $response->getBody();
              if ($returnType === '\SplFileObject') {
                // Stream goes to serializer.
                $content = $responseBody;
              }
              else {
                  $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                  $content = json_decode($content);
                }
              }

                return [
                    ObjectSerializer::deserialize($content, $returnType, []),
                    $response->getStatusCode(),
                    $response->getHeaders()
                ];
            },
            function ($exception) {
                $response = $exception->getResponse();
                $statusCode = $response->getStatusCode();
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $exception->getRequest()->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }
        );
  }

  /**
   * Create request for operation 'statusupdatesUpdateIdAcknowledgePut'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $update_id
   *   The ID of the status update being acknowledged. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function statusupdatesUpdateIdAcknowledgePutRequest($authorization, $update_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling statusupdatesUpdateIdAcknowledgePut'
      );
    }
    // Verify the required parameter 'update_id' is set.
    if ($update_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $update_id when calling statusupdatesUpdateIdAcknowledgePut'
      );
    }

    $resourcePath = '/statusupdates/{updateId}/acknowledge';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Header params.
    if ($authorization !== NULL) {
      $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
    }

    // Path params.
    if ($update_id !== NULL) {
      $resourcePath = str_replace(
        '{' . 'updateId' . '}',
        ObjectSerializer::toPathValue($update_id),
        $resourcePath
      );
    }

    // Body params.
    $_tempBody = NULL;

    if ($multipart) {
      $headers = $this->headerSelector->selectHeadersForMultipart(
        ['application/json']
      );
    }
    else {
      $headers = $this->headerSelector->selectHeaders(
        ['application/json'],
        ['application/json']
        );
    }

    // For model (json/xml)
    if (isset($_tempBody)) {
      // $_tempBody is the method argument, if present.
      $httpBody = $_tempBody;
      // \stdClass has no __toString(), so we should encode it manually.
      if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($httpBody);
      }
    }
    elseif (count($formParams) > 0) {
      if ($multipart) {
        $multipartContents = [];
        foreach ($formParams as $formParamName => $formParamValue) {
          $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
    ];
        }
        // For HTTP post (form)
        $httpBody = new MultipartStream($multipartContents);

      }
      elseif ($headers['Content-Type'] === 'application/json') {
        $httpBody = \GuzzleHttp\json_encode($formParams);

      }
      else {
        // For HTTP post (form)
        $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
      }
    }

    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('Authorization');
    if ($apiKey !== NULL) {
      $headers['Authorization'] = $apiKey;
    }
    // This endpoint requires API key authentication.
    $apiKey = $this->config->getApiKeyWithPrefix('x-api-key');
    if ($apiKey !== NULL) {
      $headers['x-api-key'] = $apiKey;
    }

    $defaultHeaders = [];
    if ($this->config->getUserAgent()) {
      $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
    }

    $headers = array_merge(
        $defaultHeaders,
        $headerParams,
        $headers
    );

    $query = \GuzzleHttp\Psr7\build_query($queryParams);
    return new Request(
        'PUT',
        $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
        $headers,
        $httpBody
    );
  }

  /**
   * Create http client option.
   *
   * @throws \RuntimeException on file opening failure
   *
   * @return array of http client options
   */
  protected function createHttpClientOption() {
    $options = [];
    if ($this->config->getDebug()) {
      $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
      if (!$options[RequestOptions::DEBUG]) {
        throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
      }
    }

    return $options;
  }

}
