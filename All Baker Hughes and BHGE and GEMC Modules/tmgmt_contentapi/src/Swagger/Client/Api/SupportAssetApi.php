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
 * SupportAssetApi Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class SupportAssetApi {
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
   * Operation jobsJobIdSupportassetsGet.
   *
   * Get all support assets in a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset[]
   */
  public function jobsJobIdSupportassetsGet($authorization, $job_id) {
    list($response) = $this->jobsJobIdSupportassetsGetWithHttpInfo($authorization, $job_id);
    return $response;
  }

  /**
   * Operation jobsJobIdSupportassetsGetWithHttpInfo.
   *
   * Get all support assets in a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset[], HTTP status code, HTTP response headers (array of strings)
   */
  public function jobsJobIdSupportassetsGetWithHttpInfo($authorization, $job_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset[]';
    $request = $this->jobsJobIdSupportassetsGetRequest($authorization, $job_id);

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
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset[]',
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
   * Operation jobsJobIdSupportassetsGetAsync.
   *
   * Get all support assets in a job.
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
  public function jobsJobIdSupportassetsGetAsync($authorization, $job_id) {
    return $this->jobsJobIdSupportassetsGetAsyncWithHttpInfo($authorization, $job_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation jobsJobIdSupportassetsGetAsyncWithHttpInfo.
   *
   * Get all support assets in a job.
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
  public function jobsJobIdSupportassetsGetAsyncWithHttpInfo($authorization, $job_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset[]';
    $request = $this->jobsJobIdSupportassetsGetRequest($authorization, $job_id);

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
   * Create request for operation 'jobsJobIdSupportassetsGet'.
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
  protected function jobsJobIdSupportassetsGetRequest($authorization, $job_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling jobsJobIdSupportassetsGet'
      );
    }
    // Verify the required parameter 'job_id' is set.
    if ($job_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $job_id when calling jobsJobIdSupportassetsGet'
      );
    }

    $resourcePath = '/jobs/{jobId}/supportassets';
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
   * Operation jobsJobIdSupportassetsPost.
   *
   * Add support asset to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateSupportAsset $body
   *   Created Support Asset object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset
   */
  public function jobsJobIdSupportassetsPost($authorization, $job_id, $body) {
    list($response) = $this->jobsJobIdSupportassetsPostWithHttpInfo($authorization, $job_id, $body);
    return $response;
  }

  /**
   * Operation jobsJobIdSupportassetsPostWithHttpInfo.
   *
   * Add support asset to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateSupportAsset $body
   *   Created Support Asset object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset, HTTP status code, HTTP response headers (array of strings)
   */
  public function jobsJobIdSupportassetsPostWithHttpInfo($authorization, $job_id, $body) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset';
    $request = $this->jobsJobIdSupportassetsPostRequest($authorization, $job_id, $body);

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
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset',
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
   * Operation jobsJobIdSupportassetsPostAsync.
   *
   * Add support asset to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateSupportAsset $body
   *   Created Support Asset object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdSupportassetsPostAsync($authorization, $job_id, $body) {
    return $this->jobsJobIdSupportassetsPostAsyncWithHttpInfo($authorization, $job_id, $body)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation jobsJobIdSupportassetsPostAsyncWithHttpInfo.
   *
   * Add support asset to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateSupportAsset $body
   *   Created Support Asset object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdSupportassetsPostAsyncWithHttpInfo($authorization, $job_id, $body) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset';
    $request = $this->jobsJobIdSupportassetsPostRequest($authorization, $job_id, $body);

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
   * Create request for operation 'jobsJobIdSupportassetsPost'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the specified job. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateSupportAsset $body
   *   Created Support Asset object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function jobsJobIdSupportassetsPostRequest($authorization, $job_id, $body) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling jobsJobIdSupportassetsPost'
      );
    }
    // Verify the required parameter 'job_id' is set.
    if ($job_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $job_id when calling jobsJobIdSupportassetsPost'
      );
    }
    // Verify the required parameter 'body' is set.
    if ($body === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $body when calling jobsJobIdSupportassetsPost'
      );
    }

    $resourcePath = '/jobs/{jobId}/supportassets';
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
   * Operation jobsJobIdSupportassetsSupportassetIdDelete.
   *
   * Delete the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return void
   */
  public function jobsJobIdSupportassetsSupportassetIdDelete($authorization, $job_id, $supportasset_id) {
    $this->jobsJobIdSupportassetsSupportassetIdDeleteWithHttpInfo($authorization, $job_id, $supportasset_id);
  }

  /**
   * Operation jobsJobIdSupportassetsSupportassetIdDeleteWithHttpInfo.
   *
   * Delete the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of null, HTTP status code, HTTP response headers (array of strings)
   */
  public function jobsJobIdSupportassetsSupportassetIdDeleteWithHttpInfo($authorization, $job_id, $supportasset_id) {
    $returnType = '';
    $request = $this->jobsJobIdSupportassetsSupportassetIdDeleteRequest($authorization, $job_id, $supportasset_id);

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

      return [NULL, $statusCode, $response->getHeaders()];

    }
    catch (ApiException $e) {
      switch ($e->getCode()) {
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
   * Operation jobsJobIdSupportassetsSupportassetIdDeleteAsync.
   *
   * Delete the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdSupportassetsSupportassetIdDeleteAsync($authorization, $job_id, $supportasset_id) {
    return $this->jobsJobIdSupportassetsSupportassetIdDeleteAsyncWithHttpInfo($authorization, $job_id, $supportasset_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation jobsJobIdSupportassetsSupportassetIdDeleteAsyncWithHttpInfo.
   *
   * Delete the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdSupportassetsSupportassetIdDeleteAsyncWithHttpInfo($authorization, $job_id, $supportasset_id) {
    $returnType = '';
    $request = $this->jobsJobIdSupportassetsSupportassetIdDeleteRequest($authorization, $job_id, $supportasset_id);

    return $this->client
      ->sendAsync($request, $this->createHttpClientOption())
      ->then(
            function ($response) use ($returnType) {
                return [NULL, $response->getStatusCode(), $response->getHeaders()];
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
   * Create request for operation 'jobsJobIdSupportassetsSupportassetIdDelete'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function jobsJobIdSupportassetsSupportassetIdDeleteRequest($authorization, $job_id, $supportasset_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling jobsJobIdSupportassetsSupportassetIdDelete'
      );
    }
    // Verify the required parameter 'job_id' is set.
    if ($job_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $job_id when calling jobsJobIdSupportassetsSupportassetIdDelete'
      );
    }
    // Verify the required parameter 'supportasset_id' is set.
    if ($supportasset_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $supportasset_id when calling jobsJobIdSupportassetsSupportassetIdDelete'
      );
    }

    $resourcePath = '/jobs/{jobId}/supportassets/{supportassetId}';
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
    // Path params.
    if ($supportasset_id !== NULL) {
      $resourcePath = str_replace(
        '{' . 'supportassetId' . '}',
        ObjectSerializer::toPathValue($supportasset_id),
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
   * Operation jobsJobIdSupportassetsSupportassetIdGet.
   *
   * Get the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset
   */
  public function jobsJobIdSupportassetsSupportassetIdGet($authorization, $job_id, $supportasset_id) {
    list($response) = $this->jobsJobIdSupportassetsSupportassetIdGetWithHttpInfo($authorization, $job_id, $supportasset_id);
    return $response;
  }

  /**
   * Operation jobsJobIdSupportassetsSupportassetIdGetWithHttpInfo.
   *
   * Get the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset, HTTP status code, HTTP response headers (array of strings)
   */
  public function jobsJobIdSupportassetsSupportassetIdGetWithHttpInfo($authorization, $job_id, $supportasset_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset';
    $request = $this->jobsJobIdSupportassetsSupportassetIdGetRequest($authorization, $job_id, $supportasset_id);

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
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset',
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
   * Operation jobsJobIdSupportassetsSupportassetIdGetAsync.
   *
   * Get the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdSupportassetsSupportassetIdGetAsync($authorization, $job_id, $supportasset_id) {
    return $this->jobsJobIdSupportassetsSupportassetIdGetAsyncWithHttpInfo($authorization, $job_id, $supportasset_id)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation jobsJobIdSupportassetsSupportassetIdGetAsyncWithHttpInfo.
   *
   * Get the support asset in a job identified by supportassetId.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdSupportassetsSupportassetIdGetAsyncWithHttpInfo($authorization, $job_id, $supportasset_id) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SupportAsset';
    $request = $this->jobsJobIdSupportassetsSupportassetIdGetRequest($authorization, $job_id, $supportasset_id);

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
   * Create request for operation 'jobsJobIdSupportassetsSupportassetIdGet'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   The ID of the job. (required)
   * @param string $supportasset_id
   *   The ID of the support asset. (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function jobsJobIdSupportassetsSupportassetIdGetRequest($authorization, $job_id, $supportasset_id) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling jobsJobIdSupportassetsSupportassetIdGet'
      );
    }
    // Verify the required parameter 'job_id' is set.
    if ($job_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $job_id when calling jobsJobIdSupportassetsSupportassetIdGet'
      );
    }
    // Verify the required parameter 'supportasset_id' is set.
    if ($supportasset_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $supportasset_id when calling jobsJobIdSupportassetsSupportassetIdGet'
      );
    }

    $resourcePath = '/jobs/{jobId}/supportassets/{supportassetId}';
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
    // Path params.
    if ($supportasset_id !== NULL) {
      $resourcePath = str_replace(
        '{' . 'supportassetId' . '}',
        ObjectSerializer::toPathValue($supportasset_id),
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
