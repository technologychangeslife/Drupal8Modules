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
 * TranslationMemoryApi Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class TranslationMemoryApi {
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
   * Operation jobsJobIdTmUpdatefilePut.
   *
   * Add a file to existing translation memory of translation job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateRequestUpdateTM $body
   *   Request UpdateTM object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return void
   */
  public function jobsJobIdTmUpdatefilePut($authorization, $job_id, $body) {
    $this->jobsJobIdTmUpdatefilePutWithHttpInfo($authorization, $job_id, $body);
  }

  /**
   * Operation jobsJobIdTmUpdatefilePutWithHttpInfo.
   *
   * Add a file to existing translation memory of translation job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateRequestUpdateTM $body
   *   Request UpdateTM object (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of null, HTTP status code, HTTP response headers (array of strings)
   */
  public function jobsJobIdTmUpdatefilePutWithHttpInfo($authorization, $job_id, $body) {
    $returnType = '';
    $request = $this->jobsJobIdTmUpdatefilePutRequest($authorization, $job_id, $body);

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
   * Operation jobsJobIdTmUpdatefilePutAsync.
   *
   * Add a file to existing translation memory of translation job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateRequestUpdateTM $body
   *   Request UpdateTM object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdTmUpdatefilePutAsync($authorization, $job_id, $body) {
    return $this->jobsJobIdTmUpdatefilePutAsyncWithHttpInfo($authorization, $job_id, $body)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation jobsJobIdTmUpdatefilePutAsyncWithHttpInfo.
   *
   * Add a file to existing translation memory of translation job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateRequestUpdateTM $body
   *   Request UpdateTM object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdTmUpdatefilePutAsyncWithHttpInfo($authorization, $job_id, $body) {
    $returnType = '';
    $request = $this->jobsJobIdTmUpdatefilePutRequest($authorization, $job_id, $body);

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
   * Create request for operation 'jobsJobIdTmUpdatefilePut'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param \Drupal\tmgmt_contentapi\Swagger\Client\Model\CreateRequestUpdateTM $body
   *   Request UpdateTM object (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function jobsJobIdTmUpdatefilePutRequest($authorization, $job_id, $body) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling jobsJobIdTmUpdatefilePut'
      );
    }
    // Verify the required parameter 'job_id' is set.
    if ($job_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $job_id when calling jobsJobIdTmUpdatefilePut'
      );
    }
    // Verify the required parameter 'body' is set.
    if ($body === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $body when calling jobsJobIdTmUpdatefilePut'
      );
    }

    $resourcePath = '/jobs/{jobId}/tm/updatefile';
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
