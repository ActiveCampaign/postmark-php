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
use Postmark\Models\PostmarkHttpResponse;

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
    protected $timeout = 30;

    /** @var Client */
    protected $client;

    protected function __construct($token, $header, $timeout = 30)
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
     */
    protected function getClient(): Client
    {
        $this->client = new Client([
            RequestOptions::VERIFY => self::$VERIFY_SSL,
            RequestOptions::TIMEOUT => $this->timeout,
        ]);

        return $this->client;
    }

    /**
     * The base request method for all API access.
     *
     * @param null|string $method The request VERB to use (GET, POST, PUT, DELETE)
     * @param null|string $path   the API path
     * @param array       $body   The content to be used (either as the query, or the json post/put body)
     *
     * @throws PostmarkException
     */
    protected function processRestRequest(string $method = null, string $path = null, array $body = []): object
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

        $httpResponse = new PostmarkHttpResponse($response);

        return $httpResponse->toArray();
    }
}
