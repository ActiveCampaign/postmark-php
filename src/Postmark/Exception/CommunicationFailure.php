<?php

declare(strict_types=1);

namespace Postmark\Exception;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;
use RuntimeException;

use function sprintf;

final class CommunicationFailure extends RuntimeException implements PostmarkException
{
    private RequestInterface $request;

    public function __construct(string $message, RequestInterface $request, ClientExceptionInterface $previous)
    {
        parent::__construct($message, (int) $previous->getCode(), $previous);
        $this->request = $request;
    }

    public static function withNetworkError(NetworkExceptionInterface $error, RequestInterface $request): self
    {
        return new self(sprintf(
            'The request failed to send due to a network error: %s',
            $error->getMessage(),
        ), $request, $error);
    }

    public static function withInvalidRequest(RequestExceptionInterface $error, RequestInterface $request): self
    {
        return new self(sprintf(
            'The request was not sent because it is considered invalid: %s',
            $error->getMessage(),
        ), $request, $error);
    }

    public static function generic(ClientExceptionInterface $error, RequestInterface $request): self
    {
        return new self(sprintf(
            'An unknown error occurred during request dispatch: %s',
            $error->getMessage(),
        ), $request, $error);
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }
}
