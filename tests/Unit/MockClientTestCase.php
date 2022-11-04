<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit;

use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

use function json_decode;
use function parse_str;

use const JSON_THROW_ON_ERROR;

abstract class MockClientTestCase extends TestCase
{
    protected MockClient $mockClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = new MockClient();
    }

    protected function assertLastRequest(): RequestInterface
    {
        $request = $this->mockClient->getLastRequest();
        self::assertInstanceOf(RequestInterface::class, $request);

        return $request;
    }

    protected function assertLastRequestMethodWas(string $method): void
    {
        $request = $this->assertLastRequest();
        self::assertEquals($method, $request->getMethod());
    }

    protected function assertLastRequestPathEquals(string $expected): void
    {
        $request = $this->assertLastRequest();
        self::assertEquals($expected, $request->getUri()->getPath());
    }

    /** @return array<string, mixed> */
    protected function bodyParams(): array
    {
        $body = $this->assertLastRequest()->getBody();
        self::assertJson((string) $body, 'Request body was not JSON');

        /** @psalm-var array<string, mixed> $data */
        $data = json_decode((string) $body, true, 512, JSON_THROW_ON_ERROR);

        return $data;
    }

    /** @return array<array-key, mixed> */
    protected function queryParams(): array
    {
        $query = $this->assertLastRequest()->getUri()->getQuery();
        if (empty($query)) {
            return [];
        }

        parse_str($query, $values);

        return $values;
    }

    protected function assertQueryParameterValueEquals(string $name, string $expect): void
    {
        $query = $this->queryParams();
        self::assertArrayHasKey($name, $query);
        self::assertEquals($expect, $query[$name]);
    }

    protected function assertQueryParameterIsAbsent(string $name): void
    {
        $query = $this->queryParams();
        self::assertArrayNotHasKey($name, $query);
    }

    protected function assertBodyParameterValueEquals(string $name, mixed $expect): void
    {
        $body = $this->bodyParams();
        self::assertArrayHasKey($name, $body);
        self::assertEquals($expect, $body[$name]);
    }

    protected function assertBodyParameterIsAbsent(string $name): void
    {
        $body = $this->bodyParams();
        self::assertArrayNotHasKey($name, $body);
    }
}
