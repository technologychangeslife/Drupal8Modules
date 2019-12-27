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
 * ProviderApi Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class ProviderApi {
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
   * Operation providersGet.
   *
   * Get all configured translation providers.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider[]
   */
  public function providersGet($authorization) {
    list($response) = $this->providersGetWithHttpInfo($authorization);
    return $response;
  }

  /**
   * Operation providersGetWithHttpInfo.
   *
   * Get all configured translation providers.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider[], HTTP status code, HTTP response headers (array of strings)
   */
  public function providersGetWithHttpInfo($authorization) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider[]';
    $request = $this->providersGetRequest($authorization);

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
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider[]',
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
   * Operation providersGetAsync.
   *
   * Get all configured translation providers.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function providersGetAsync($authorization) {
    return $this->providersGetAsyncWithHttpInfo($authorization)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation providersGetAsyncWithHttpInfo.
   *
   * Get all configured translation providers.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function providersGetAsyncWithHttpInfo($authorization) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider[]';
    $request = $this->providersGetRequest($authorization);

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
   * Create request for operation 'providersGet'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function providersGetRequest($authorization) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling providersGet'
      );
    }

    $resourcePath = '/providers';
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
   * Operation providersProviderIdGet.
   *
   * Get a translation provider.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $provider_id
   *   The ID of the translation provider. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider
   */
  public function providersProviderIdGet($authorization, $provider_id) {
    list($response) = $this->providersProviderIdGetWithHttpInfo($authorization, $provider_id);
    return $response;
  }

  /**
   * Operation providersProviderIdGetWithHttpInfo.
   *
   * Get a translation provider.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $provider_id
   *   The ID of the translation provider. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider, HTTP status code, HTTP response headers (array of strings)
   */
  public function providersProviderIdGetWithHttpInfo($authorization, $provider_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider';
    $request = $this->providersProviderIdGetRequest($authorization, $provider_id);

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
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider',
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
   * Operation providersProviderIdGetAsync.
   *
   * Get a translation provider.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $provider_id
   *   The ID of the translation provider. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function providersProviderIdGetAsync($authorization, $provider_id) {
    return $this->providersProviderIdGetAsyncWithHttpInfo($authorization, $provider_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation providersProviderIdGetAsyncWithHttpInfo.
   *
   * Get a translation provider.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $provider_id
   *   The ID of the translation provider. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function providersProviderIdGetAsyncWithHttpInfo($authorization, $provider_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\Provider';
    $request = $this->providersProviderIdGetRequest($authorization, $provider_id);

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
   * Create request for operation 'providersProviderIdGet'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $provider_id
   *   The ID of the translation provider. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function providersProviderIdGetRequest($authorization, $provider_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling providersProviderIdGet'
      );
    }
    // Verify the required parameter 'provider_id' is set.
    if ($provider_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $provider_id when calling providersProviderIdGet'
      );
    }

    $resourcePath = '/providers/{providerId}';
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
    if ($provider_id !== NULL) {
      $resourcePath = str_replace(
        '{' . 'providerId' . '}',
        ObjectSerializer::toPathValue($provider_id),
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
