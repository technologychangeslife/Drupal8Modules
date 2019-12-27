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
 * SourceFileApi Class Doc Comment.
 *
 * @category Class
 * @package Drupal\tmgmt_contentapi\Swagger\Client
 * @author Swagger Codegen team
 * @link https://github.com/swagger-api/swagger-codegen
 */
class SourceFileApi {
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
   * Operation jobsJobIdUploadPost.
   *
   * Upload a binary file to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param string $file_name
   *   Name of file. (required)
   * @param string $file_type
   *   MIME type of the source file of the request. If empty, a default will be supplied based on the extension extracted from the name of the request, or &#39;application/octet-stream&#39; if no extension can be found from the name of the request. (required)
   * @param \SplFileObject $source_file
   *   The file of the source content for the request(s). *Note*: \&quot;Try it out\&quot; for this method on developers.lionbridge.com is currently not functioning. To test this method, use the cURL sample on https://bitbucket.org/liox-ondemand/liox-content-api-client/src/master/curl/File%20Workflow (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return \Drupal\tmgmt_contentapi\Swagger\Client\Model\SourceFile
   */
  public function jobsJobIdUploadPost($authorization, $job_id, $file_name, $file_type, $source_file) {
    list($response) = $this->jobsJobIdUploadPostWithHttpInfo($authorization, $job_id, $file_name, $file_type, $source_file);
    return $response;
  }

  /**
   * Operation jobsJobIdUploadPostWithHttpInfo.
   *
   * Upload a binary file to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param string $file_name
   *   Name of file. (required)
   * @param string $file_type
   *   MIME type of the source file of the request. If empty, a default will be supplied based on the extension extracted from the name of the request, or &#39;application/octet-stream&#39; if no extension can be found from the name of the request. (required)
   * @param \SplFileObject $source_file
   *   The file of the source content for the request(s). *Note*: \&quot;Try it out\&quot; for this method on developers.lionbridge.com is currently not functioning. To test this method, use the cURL sample on https://bitbucket.org/liox-ondemand/liox-content-api-client/src/master/curl/File%20Workflow (required)
   *
   * @throws \Drupal\tmgmt_contentapi\Swagger\Client\ApiException on non-2xx response
   * @throws \InvalidArgumentException
   *
   * @return array of \Drupal\tmgmt_contentapi\Swagger\Client\Model\SourceFile, HTTP status code, HTTP response headers (array of strings)
   */
  public function jobsJobIdUploadPostWithHttpInfo($authorization, $job_id, $file_name, $file_type, $source_file) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SourceFile';
    $request = $this->jobsJobIdUploadPostRequest($authorization, $job_id, $file_name, $file_type, $source_file);

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
          '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SourceFile',
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
   * Operation jobsJobIdUploadPostAsync.
   *
   * Upload a binary file to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param string $file_name
   *   Name of file. (required)
   * @param string $file_type
   *   MIME type of the source file of the request. If empty, a default will be supplied based on the extension extracted from the name of the request, or &#39;application/octet-stream&#39; if no extension can be found from the name of the request. (required)
   * @param \SplFileObject $source_file
   *   The file of the source content for the request(s). *Note*: \&quot;Try it out\&quot; for this method on developers.lionbridge.com is currently not functioning. To test this method, use the cURL sample on https://bitbucket.org/liox-ondemand/liox-content-api-client/src/master/curl/File%20Workflow (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdUploadPostAsync($authorization, $job_id, $file_name, $file_type, $source_file) {
    return $this->jobsJobIdUploadPostAsyncWithHttpInfo($authorization, $job_id, $file_name, $file_type, $source_file)
      ->then(
            function ($response) {
                return $response[0];
            }
        );
  }

  /**
   * Operation jobsJobIdUploadPostAsyncWithHttpInfo.
   *
   * Upload a binary file to a job.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param string $file_name
   *   Name of file. (required)
   * @param string $file_type
   *   MIME type of the source file of the request. If empty, a default will be supplied based on the extension extracted from the name of the request, or &#39;application/octet-stream&#39; if no extension can be found from the name of the request. (required)
   * @param \SplFileObject $source_file
   *   The file of the source content for the request(s). *Note*: \&quot;Try it out\&quot; for this method on developers.lionbridge.com is currently not functioning. To test this method, use the cURL sample on https://bitbucket.org/liox-ondemand/liox-content-api-client/src/master/curl/File%20Workflow (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Promise\PromiseInterface
   */
  public function jobsJobIdUploadPostAsyncWithHttpInfo($authorization, $job_id, $file_name, $file_type, $source_file) {
    $returnType = '\Drupal\tmgmt_contentapi\Swagger\Client\Model\SourceFile';
    $request = $this->jobsJobIdUploadPostRequest($authorization, $job_id, $file_name, $file_type, $source_file);

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
   * Create request for operation 'jobsJobIdUploadPost'.
   *
   * @param string $authorization
   *   Oauth2 token (required)
   * @param string $job_id
   *   Job ID. (required)
   * @param string $file_name
   *   Name of file. (required)
   * @param string $file_type
   *   MIME type of the source file of the request. If empty, a default will be supplied based on the extension extracted from the name of the request, or &#39;application/octet-stream&#39; if no extension can be found from the name of the request. (required)
   * @param \SplFileObject $source_file
   *   The file of the source content for the request(s). *Note*: \&quot;Try it out\&quot; for this method on developers.lionbridge.com is currently not functioning. To test this method, use the cURL sample on https://bitbucket.org/liox-ondemand/liox-content-api-client/src/master/curl/File%20Workflow (required)
   *
   * @throws \InvalidArgumentException
   *
   * @return \GuzzleHttp\Psr7\Request
   */
  protected function jobsJobIdUploadPostRequest($authorization, $job_id, $file_name, $file_type, $source_file) {
    // Verify the required parameter 'authorization' is set.
    if ($authorization === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $authorization when calling jobsJobIdUploadPost'
      );
    }
    // Verify the required parameter 'job_id' is set.
    if ($job_id === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $job_id when calling jobsJobIdUploadPost'
      );
    }
    // Verify the required parameter 'file_name' is set.
    if ($file_name === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $file_name when calling jobsJobIdUploadPost'
      );
    }
    // Verify the required parameter 'file_type' is set.
    if ($file_type === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $file_type when calling jobsJobIdUploadPost'
      );
    }
    // Verify the required parameter 'source_file' is set.
    if ($source_file === NULL) {
      throw new \InvalidArgumentException(
        'Missing the required parameter $source_file when calling jobsJobIdUploadPost'
      );
    }

    $resourcePath = '/jobs/{jobId}/upload';
    $formParams = [];
    $queryParams = [];
    $headerParams = [];
    $httpBody = '';
    $multipart = FALSE;

    // Query params.
    if ($file_name !== NULL) {
      $queryParams['fileName'] = ObjectSerializer::toQueryValue($file_name);
    }
    // Query params.
    if ($file_type !== NULL) {
      $queryParams['fileType'] = ObjectSerializer::toQueryValue($file_type);
    }
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

    // Form params.
    if ($source_file !== NULL) {
      $multipart = TRUE;
      $formParams['sourceFile'] = \GuzzleHttp\Psr7\try_fopen(ObjectSerializer::toFormValue($source_file), 'rb');
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
        ['multipart/form-data']
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
