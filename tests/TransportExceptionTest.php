<?php

namespace Postmark\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Postmark\Models\PostmarkException;
use Postmark\Models\PostmarkTransportException;
use Postmark\PostmarkClient;

/**
 * The SDK must not leak its HTTP client's exception hierarchy.
 *
 * Guzzle 8 reclassified transport exceptions — a plain timeout stopped being a
 * ConnectException — and because the SDK disables http_errors, that family is
 * the only one that can reach a caller. These run identically under Guzzle 7
 * and 8; the CI matrix exercises both. No credentials required.
 */
class TransportExceptionTest extends TestCase
{
    public function testTransportFailureIsWrappedInAPostmarkType(): void
    {
        $client = $this->clientThatFailsWith(
            new ConnectException('cURL error 7: Failed to connect', new Request('POST', 'test'))
        );

        try {
            $client->sendEmail('a@example.com', 'b@example.com', 'Subject', null, 'Body');
            $this->fail('Expected a PostmarkTransportException.');
        } catch (PostmarkTransportException $e) {
            $this->assertStringContainsString('Could not reach the Postmark API', $e->getMessage());
            $this->assertInstanceOf(ConnectException::class, $e->getPrevious());
        }
    }

    /** Existing `catch (PostmarkException)` blocks must keep working. */
    public function testItIsCatchableAsAPostmarkException(): void
    {
        $client = $this->clientThatFailsWith(
            new ConnectException('cURL error 7', new Request('POST', 'test'))
        );

        $this->expectException(PostmarkException::class);
        $client->sendEmail('a@example.com', 'b@example.com', 'Subject', null, 'Body');
    }

    /**
     * The point of the change: a caller can classify the failure without
     * importing anything from GuzzleHttp, and gets the same answer on either
     * major even though the underlying class differs.
     */
    public function testCallersCanClassifyWithoutTouchingGuzzle(): void
    {
        $timeout = $this->captureFrom($this->connectFailure('cURL error 28: Operation timed out', 28));

        $this->assertTrue($timeout->isTimeout());
        $this->assertFalse($timeout->isConnectionFailure());

        $refused = $this->captureFrom($this->connectFailure('cURL error 7: Failed to connect', 7));

        $this->assertTrue($refused->isConnectionFailure());
        $this->assertFalse($refused->isTimeout());
    }

    /** Handlers that report no errno still classify, via the message. */
    public function testClassificationFallsBackToTheMessageWithoutAnErrno(): void
    {
        $timeout = $this->captureFrom(
            new ConnectException('cURL error 28: Operation timed out', new Request('POST', 'test'))
        );

        $this->assertTrue($timeout->isTimeout());
    }

    /** A response that arrives carrying an error is NOT a transport failure. */
    public function testApiErrorsAreStillPlainPostmarkExceptions(): void
    {
        $mock = new MockHandler([new \GuzzleHttp\Psr7\Response(401, [], '{}')]);
        $guzzle = new Client(['handler' => HandlerStack::create($mock)]);
        $client = new PostmarkClient('test-token');
        $client->setClient($guzzle);

        try {
            $client->sendEmail('a@example.com', 'b@example.com', 'Subject', null, 'Body');
            $this->fail('Expected a PostmarkException.');
        } catch (PostmarkTransportException $e) {
            $this->fail('A 401 is a response, not a transport failure.');
        } catch (PostmarkException $e) {
            $this->assertSame(401, $e->getHttpStatusCode());
        }
    }

    /**
     * Guzzle 7's ConnectException accepts a handler context (which is where the
     * cURL errno lives); Guzzle 8 removed that parameter and uses precise
     * subclasses instead. Build whichever the installed major supports.
     */
    private function connectFailure(string $message, int $errno): GuzzleException
    {
        $class = new \ReflectionClass(ConnectException::class);
        $arguments = [$message, new Request('POST', 'test')];

        // Built reflectively rather than with a literal 4-argument call: PHPStan
        // resolves the constructor against whichever major is installed and would
        // reject the wider form under Guzzle 8, even guarded.
        if ($class->getConstructor()->getNumberOfParameters() >= 4) {
            $arguments[] = null;
            $arguments[] = ['errno' => $errno];
        }

        return $class->newInstanceArgs($arguments);
    }

    private function captureFrom(GuzzleException $failure): PostmarkTransportException
    {
        try {
            $this->clientThatFailsWith($failure)
                ->sendEmail('a@example.com', 'b@example.com', 'Subject', null, 'Body');
        } catch (PostmarkTransportException $e) {
            return $e;
        }

        $this->fail('Expected a PostmarkTransportException.');
    }

    private function clientThatFailsWith(GuzzleException $failure): PostmarkClient
    {
        $guzzle = new Client(['handler' => HandlerStack::create(new MockHandler([$failure]))]);
        $client = new PostmarkClient('test-token');
        $client->setClient($guzzle);

        return $client;
    }
}
