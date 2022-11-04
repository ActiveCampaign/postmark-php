<?php

declare(strict_types=1);

namespace Postmark\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

use function is_numeric;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

final class RequestFailure extends RuntimeException implements PostmarkException
{
    public function __construct(string $message, private RequestInterface $request, private ResponseInterface $response)
    {
        parent::__construct($message, $response->getStatusCode());
    }

    public static function with(RequestInterface $request, ResponseInterface $response): self
    {
        switch ($response->getStatusCode()) {
            case 401:
                return new self(
                    'Unauthorized: Missing or incorrect API token in header. '
                    . 'Please verify that you used the correct token when you constructed your client.',
                    $request,
                    $response,
                );

            case 500:
                return new self(
                    'Internal Server Error: This is an issue with Postmark’s servers processing your request. '
                    . 'In most cases the message is lost during the process, '
                    . 'and Postmark is notified so that we can investigate the issue.',
                    $request,
                    $response,
                );

            case 503:
                return new self(
                    'The Postmark API is currently unavailable, please try your request later.',
                    $request,
                    $response,
                );

            default:
                return new self(
                    self::retrieveErrorMessage($response)
                        ?: 'A request error occurred and there was no message encoded in the response.',
                    $request,
                    $response,
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

    /** @return non-empty-string|null */
    public function postmarkErrorMessage(): string|null
    {
        return self::retrieveErrorMessage($this->response);
    }

    public function postmarkErrorCode(): int|null
    {
        $body = self::responseBody($this->response);
        $code = isset($body['ErrorCode']) && is_numeric($body['ErrorCode']) && ! empty($body['ErrorCode'])
            ? $body['ErrorCode']
            : null;

        return $code ? (int) $code : null;
    }

    /** @return non-empty-string|null */
    private static function retrieveErrorMessage(ResponseInterface $response): string|null
    {
        $body = self::responseBody($response);

        return isset($body['Message']) && is_string($body['Message']) && ! empty($body['Message'])
            ? $body['Message']
            : null;
    }

    /** @return array<array-key, mixed> */
    private static function responseBody(ResponseInterface $response): array
    {
        $payload = (string) $response->getBody();
        if (empty($payload)) {
            return [];
        }

        /** @psalm-var array<array-key, mixed> $body */
        $body = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        return $body;
    }
}
