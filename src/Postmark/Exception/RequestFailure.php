<?php

declare(strict_types=1);

namespace Postmark\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

use function json_decode;

use const JSON_THROW_ON_ERROR;

final class RequestFailure extends RuntimeException implements PostmarkException
{
    private RequestInterface $request;
    private ResponseInterface $response;

    public function __construct(string $message, RequestInterface $request, ResponseInterface $response)
    {
        parent::__construct($message, $response->getStatusCode());
        $this->request = $request;
        $this->response = $response;
    }

    public static function with(RequestInterface $request, ResponseInterface $response): self
    {
        switch ($response->getStatusCode()) {
            case 401:
                return new self(
                    'Unauthorized: Missing or incorrect API token in header. '
                    . 'Please verify that you used the correct token when you constructed your client.',
                    $request,
                    $response
                );
            case 500:
                return new self(
                    'Internal Server Error: This is an issue with Postmark’s servers processing your request. '
                    . 'In most cases the message is lost during the process, '
                    . 'and Postmark is notified so that we can investigate the issue.',
                    $request,
                    $response
                );
            case 503:
                return new self(
                    'The Postmark API is currently unavailable, please try your request later.',
                    $request,
                    $response
                );
            default:
                $body = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

                return new self(
                    $body['Message'] ?? 'A request error occurred and there was no message encoded in the response.',
                    $request,
                    $response
                );
        }
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }

    public function response(): ResponseInterface
    {
        return $this->response;
    }
}
