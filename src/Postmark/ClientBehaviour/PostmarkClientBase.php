<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Fig\Http\Message\RequestMethodInterface;
use Postmark\Exception\CommunicationFailure;
use Postmark\Exception\DiscoveryFailure;
use Postmark\Exception\InvalidRequestMethod;
use Postmark\Exception\RequestFailure;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

use function array_filter;
use function http_build_query;
use function json_decode;
use function json_encode;
use function sprintf;

use const JSON_BIGINT_AS_STRING;
use const JSON_THROW_ON_ERROR;
use const PHP_MAJOR_VERSION;
use const PHP_MINOR_VERSION;
use const PHP_OS_FAMILY;
use const PHP_QUERY_RFC3986;
use const PHP_RELEASE_VERSION;

/** @internal Postmark */
abstract class PostmarkClientBase
{
    use Discovery;

    public const DEFAULT_BASE_URI = 'https://api.postmarkapp.com';

    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;
    private UriFactoryInterface $uriFactory;
    private StreamFactoryInterface $streamFactory;
    private string $baseUri = self::DEFAULT_BASE_URI;
    /** @var non-empty-string */
    private string $token;

    /**
     * @param non-empty-string     $token      Either a 'Server' token or an 'Account' token.
     * @param ClientInterface|null $httpClient You can provide any PSR-18 Http Client, otherwise an HTTP client will be
     *                                         discovered from your environment. If no client can be located and none is
     *                                         given, an exception will be thrown.
     *
     * @throws DiscoveryFailure If any HTTP related components cannot be discovered from your environment.
     */
    final public function __construct(
        string $token,
        ?ClientInterface $httpClient = null
    ) {
        $this->client = self::resolveHttpClient($httpClient);
        $this->requestFactory = self::resolveRequestFactory();
        $this->uriFactory = self::resolveUriFactory();
        $this->streamFactory = self::resolveStreamFactory();
        $this->token = $token;
    }

    /** @return non-empty-string */
    abstract protected function authorizationHeaderName(): string;

    public function baseUri(): UriInterface
    {
        return $this->uriFactory->createUri($this->baseUri);
    }

    /** @return static */
    public function withBaseUri(string $baseUri): self
    {
        $client = new static($this->token, $this->client);
        $client->baseUri = $baseUri;

        return $client;
    }

    /**
     * The base request method for all API access.
     *
     * @param non-empty-string        $method The request VERB to use (GET, POST, PUT, DELETE)
     * @param string                  $path   The API path.
     * @param array<array-key, mixed> $params The content to be used (either as the query, or the json post/put body)
     *
     * @return array<array-key, mixed>
     *
     * @throws RequestFailure if for any reason the request is rejected or considered erroneous by Postmark.
     * @throws CommunicationFailure if it was not possible to send the request at all.
     */
    protected function processRestRequest(string $method, string $path, array $params = []): array
    {
        $target = $this->baseUri()->withPath($path);
        $query = $body = null;

        $params = array_filter($params);
        switch ($method) {
            case RequestMethodInterface::METHOD_GET:
            case RequestMethodInterface::METHOD_HEAD:
            case RequestMethodInterface::METHOD_DELETE:
            case RequestMethodInterface::METHOD_OPTIONS:
                $query = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
                break;
            case RequestMethodInterface::METHOD_PUT:
            case RequestMethodInterface::METHOD_POST:
            case RequestMethodInterface::METHOD_PATCH:
                $body = $this->streamFactory->createStream(
                    json_encode($params, JSON_THROW_ON_ERROR)
                );
                break;

            default:
                throw InvalidRequestMethod::with($method);
        }

        if (! empty($query)) {
            $target = $target->withQuery($query);
        }

        $request = $this->requestFactory->createRequest($method, $target)
            ->withHeader('User-Agent', sprintf(
                'Postmark-PHP (PHP Version:%d.%d.%d, OS:%s)',
                PHP_MAJOR_VERSION,
                PHP_MINOR_VERSION,
                PHP_RELEASE_VERSION,
                PHP_OS_FAMILY
            ))
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json')
            ->withHeader($this->authorizationHeaderName(), $this->token);

        if ($body !== null) {
            $request = $request->withBody($body);
        }

        try {
            $response = $this->client->sendRequest($request);
        } catch (NetworkExceptionInterface $error) {
            throw CommunicationFailure::withNetworkError($error, $request);
        } catch (RequestExceptionInterface $error) {
            throw CommunicationFailure::withInvalidRequest($error, $request);
        } catch (ClientExceptionInterface $error) {
            throw CommunicationFailure::generic($error, $request);
        }

        if ($response->getStatusCode() === 200) {
            // Casting BIGINT as STRING instead of the default FLOAT, to avoid loss of precision.
            /** @psalm-var array<array-key, mixed> $body */
            $body = json_decode(
                (string) $response->getBody(),
                true,
                512,
                JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING
            );

            return $body;
        }

        throw RequestFailure::with($request, $response);
    }
}
