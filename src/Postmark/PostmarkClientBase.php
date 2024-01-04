<?php

/*
 * Author:   Wildbit (http://wildbit.com)
 * License:  http://creativecommons.org/licenses/MIT/ MIT
 * Link:     https://github.com/wildbit/postmark-php/
 */

namespace Postmark;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Postmark\Models\PostmarkException;

/**
 * This is the core class that interacts with the Postmark API. All clients should
 * inherit fromt this class.
 */
abstract class PostmarkClientBase
{
    /**
     * BASE_URL is "https://api.postmarkapp.com".
     *
     * You may modify this value to disable SSL support, but it is not recommended.
     *
     * @var string
     */
    public static $BASE_URL = 'https://api.postmarkapp.com';

    /**
     * VERIFY_SSL is defaulted to "true".
     *
     * In some PHP configurations, SSL/TLS certificates cannot be verified.
     * Rather than disabling SSL/TLS entirely in these circumstances, you may
     * disable certificate verification. This is dramatically better than disabling
     * connecting to the Postmark API without TLS, as it's still encrypted,
     * but the risk is that if your connection has been compromised, your application could
     * be subject to a Man-in-the-middle attack. However, this is still a better outcome
     * than using no encryption at all.
     *
     * If possible, you should try to resolve your PHP install's certificate issues as outline here:
     * https://github.com/wildbit/postmark-php/wiki/SSL%20Errors%20on%20Windows
     */
    public static $VERIFY_SSL = true;

    protected $authorization_token;
    protected $authorization_header;
    protected $version;
    protected $os;
    protected $timeout = 60;

    protected Client $client;

    protected function __construct($token, $header, $timeout = 60)
    {
        $this->authorization_header = $header;
        $this->authorization_token = $token;
        $this->version = phpversion();
        $this->os = PHP_OS;
        $this->timeout = $timeout;
    }

    /**
     * Provide a custom GuzzleHttp\Client to be used for HTTP requests.
     *
     * @see http://docs.guzzlephp.org/en/latest/ for a full list of configuration options
     *
     * The following options will be ignored:
     * - http_errors
     * - headers
     * - query
     * - json
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Return the injected GuzzleHttp\Client or create a default instance.
     *
     * @return Client
     */
    protected function getClient()
    {
        if (empty($this->client)) {
            $this->client = new Client([
                RequestOptions::VERIFY => self::$VERIFY_SSL,
                RequestOptions::TIMEOUT => $this->timeout,
            ]);
        }

        return $this->client;
    }

    /**
     * The base request method for all API access.
     *
     * @param string $method The request VERB to use (GET, POST, PUT, DELETE)
     * @param string $path   the API path
     * @param array  $body   The content to be used (either as the query, or the json post/put body)
     *
     * @return object
     *
     * @throws PostmarkException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function processRestRequest($method = null, $path = null, array $body = [])
    {
        $client = $this->getClient();

        $options = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => [
                'User-Agent' => "Postmark-PHP (PHP Version:{$this->version}, OS:{$this->os})",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                $this->authorization_header => $this->authorization_token,
            ],
        ];

        if (!empty($body)) {
            $cleanParams = array_filter($body, function ($value) {
                return null !== $value;
            });

            switch ($method) {
                case 'GET':
                case 'HEAD':
                case 'DELETE':
                case 'OPTIONS':
                    $options[RequestOptions::QUERY] = $cleanParams;

                    break;

                case 'PUT':
                case 'POST':
                case 'PATCH':
                    $options[RequestOptions::JSON] = $cleanParams;

                    break;
            }
        }

        $response = $client->request($method, self::$BASE_URL . $path, $options);

        switch ($response->getStatusCode()) {
            case 200:
                // Casting BIGINT as STRING instead of the default FLOAT, to avoid loss of precision.
                return json_decode((string) $response->getBody(), true, 512, JSON_BIGINT_AS_STRING);

            case 401:
                $ex = new PostmarkException();
                $ex->message = 'Unauthorized: Missing or incorrect API token in header. ' .
                'Please verify that you used the correct token when you constructed your client.';
                $ex->setHttpStatusCode(401);

                throw $ex;

            case 500:
                $ex = new PostmarkException();
                $ex->setHttpStatusCode(500);
                $ex->message = 'Internal Server Error: This is an issue with Postmark’s servers processing your request. ' .
                'In most cases the message is lost during the process, ' .
                'and Postmark is notified so that we can investigate the issue.';

                throw $ex;

            case 503:
                $ex = new PostmarkException();
                $ex->setHttpStatusCode(503);
                $ex->message = 'The Postmark API is currently unavailable, please try your request later.';

                throw $ex;

                // This should cover case 422, and any others that are possible:
            default:
                $ex = new PostmarkException();
                $body = json_decode((string) $response->getBody(), true);
                $ex->setHttpStatusCode($response->getStatusCode());
                $ex->setPostmarkApiErrorCode($body['ErrorCode'] ?? 422);
                $ex->message = $body['Message'];

                throw $ex;
        }
    }
}
