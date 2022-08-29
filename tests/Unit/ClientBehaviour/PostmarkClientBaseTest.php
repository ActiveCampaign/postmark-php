<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Fig\Http\Message\RequestMethodInterface;
use Http\Client\Exception\NetworkException;
use Http\Client\Exception\RequestException;
use Http\Client\Exception\TransferException;
use Postmark\ClientBehaviour\PostmarkClientBase;
use Postmark\Exception\CommunicationFailure;
use Postmark\Exception\InvalidRequestMethod;
use Postmark\Exception\RequestFailure;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;
use Psr\Http\Message\RequestInterface;

class PostmarkClientBaseTest extends MockClientTestCase
{
    private StubClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $stubClient = new StubClient('token', $this->mockClient);
        $this->client = $stubClient->withBaseUri('https://example.com');
    }

    public function testThatByDefaultTheUriWillPointToThePostmarkApi(): void
    {
        $expect = PostmarkClientBase::DEFAULT_BASE_URI;
        self::assertEquals(
            $expect,
            (string) (new StubClient('foo'))->baseUri(),
        );
    }

    public function testThatACloneCanBeRetrievedWithADifferentBaseUri(): void
    {
        $client = new StubClient('foo');
        $clone = $client->withBaseUri('https://example.com');

        self::assertInstanceOf(StubClient::class, $clone);
        self::assertNotSame($client, $clone);
        self::assertEquals('https://example.com', (string) $clone->baseUri());
        self::assertEquals(PostmarkClientBase::DEFAULT_BASE_URI, (string) $client->baseUri());
    }

    public function testThatAWeirdHttpMethodWillNotBeAccepted(): void
    {
        $this->expectException(InvalidRequestMethod::class);
        $this->expectExceptionMessage('The request method "Goats" is invalid');
        $this->client->send('Goats', '/foo', []);
    }

    public function testThatAnEmptyHttpMethodWillNotBeAccepted(): void
    {
        $this->expectException(InvalidRequestMethod::class);
        $this->expectExceptionMessage('The request method "" is invalid');
        /** @psalm-suppress InvalidArgument */
        $this->client->send('', '/foo', []);
    }

    public function testThatThePathWillBeAppendedToTheBaseUri(): void
    {
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
        $this->client->send('GET', '/foo', []);
        $request = $this->assertLastRequest();
        self::assertEquals('https://example.com/foo', (string) $request->getUri());
    }

    /** @return array<string, array{0: non-empty-string}> */
    public function requestBodyMethodProvider(): array
    {
        return [
            RequestMethodInterface::METHOD_PUT => [RequestMethodInterface::METHOD_PUT],
            RequestMethodInterface::METHOD_POST => [RequestMethodInterface::METHOD_POST],
            RequestMethodInterface::METHOD_PATCH => [RequestMethodInterface::METHOD_PATCH],
        ];
    }

    /** @return array<string, array{0: non-empty-string}> */
    public function queryParamMethodProvider(): array
    {
        return [
            RequestMethodInterface::METHOD_GET => [RequestMethodInterface::METHOD_GET],
            RequestMethodInterface::METHOD_HEAD => [RequestMethodInterface::METHOD_HEAD],
            RequestMethodInterface::METHOD_DELETE => [RequestMethodInterface::METHOD_DELETE],
            RequestMethodInterface::METHOD_OPTIONS => [RequestMethodInterface::METHOD_OPTIONS],
        ];
    }

    /**
     * @param non-empty-string $method
     *
     * @dataProvider requestBodyMethodProvider
     */
    public function testThatParamsAreEncodedInTheBodyWhenAppropriate(string $method): void
    {
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
        $this->client->send($method, '/foo', ['foo' => 'bar']);
        $this->assertBodyParameterValueEquals('foo', 'bar');
    }

    public function testThatOnlyNullParametersAreExcludedFromTheBodyPayload(): void
    {
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
        $this->client->send('POST', '/foo', [
            'Null' => null,
            'True' => true,
            'False' => false,
            'Zero' => 0,
            'EmptyString' => '',
        ]);

        $this->assertBodyParameterIsAbsent('Null');
        $this->assertBodyParameterValueEquals('True', true);
        $this->assertBodyParameterValueEquals('False', false);
        $this->assertBodyParameterValueEquals('Zero', 0);
        $this->assertBodyParameterValueEquals('EmptyString', '');
    }

    /**
     * @param non-empty-string $method
     *
     * @dataProvider queryParamMethodProvider
     */
    public function testThatParamsAreEncodedInTheQueryWhenAppropriate(string $method): void
    {
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
        $this->client->send($method, '/foo', ['foo' => 'bar', 'baz' => 'bat']);
        $expectQuery = 'foo=bar&baz=bat';
        $request = $this->assertLastRequest();
        self::assertEquals($expectQuery, $request->getUri()->getQuery());
    }

    public function testThatARequestFailureWillBeIssuedForApiErrorConditions(): void
    {
        $response = ResponseFixture::fromFileName('InvalidToken.json', 401)->toResponse();
        $this->mockClient->setDefaultResponse($response);
        $this->expectException(RequestFailure::class);
        $this->client->send('GET', '/foo');
    }

    public function testThatNetworkErrorsAreCaughtAndRethrown(): void
    {
        $exception = new NetworkException('Network Error', $this->createMock(RequestInterface::class));
        $this->mockClient->setDefaultException($exception);
        try {
            $this->client->send('GET', '/foo');
            $this->fail('An exception was not thrown');
        } catch (CommunicationFailure $error) {
            self::assertSame($exception, $error->getPrevious());
            self::assertEquals('The request failed to send due to a network error: Network Error', $error->getMessage());
            self::assertSame($this->assertLastRequest(), $error->request());
        }
    }

    public function testThatInvalidRequestsAreCaughtAndRethrown(): void
    {
        $exception = new RequestException('Invalid Request', $this->createMock(RequestInterface::class));
        $this->mockClient->setDefaultException($exception);
        try {
            $this->client->send('GET', '/foo');
            $this->fail('An exception was not thrown');
        } catch (CommunicationFailure $error) {
            self::assertSame($exception, $error->getPrevious());
            self::assertEquals('The request was not sent because it is considered invalid: Invalid Request', $error->getMessage());
            self::assertSame($this->assertLastRequest(), $error->request());
        }
    }

    public function testThatOtherClientFailuresAreCaughtAndRethrown(): void
    {
        $exception = new TransferException('Other Error');
        $this->mockClient->setDefaultException($exception);
        try {
            $this->client->send('GET', '/foo');
            $this->fail('An exception was not thrown');
        } catch (CommunicationFailure $error) {
            self::assertSame($exception, $error->getPrevious());
            self::assertEquals('An unknown error occurred during request dispatch: Other Error', $error->getMessage());
            self::assertSame($this->assertLastRequest(), $error->request());
        }
    }
}
