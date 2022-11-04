<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit;

use InvalidArgumentException;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

use function file_exists;
use function file_get_contents;
use function sprintf;

final class ResponseFixture
{
    private StreamInterface $jsonBody;

    public function __construct(
        private int $responseCode,
        string $jsonBody,
    ) {
        $this->jsonBody = (new StreamFactory())->createStream($jsonBody);
    }

    public function toResponse(): ResponseInterface
    {
        return (new ResponseFactory())->createResponse($this->responseCode)
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withBody($this->jsonBody);
    }

    public static function fromFileName(string $filename, int $statusCode): self
    {
        $path = sprintf('%s/%s', __DIR__ . '/../fixture', $filename);

        if (! file_exists($path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" cannot be found', $filename));
        }

        return new self($statusCode, file_get_contents($path));
    }
}
