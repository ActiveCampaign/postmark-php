<?php

namespace Postmark\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Postmark\PostmarkClient;
use Psr\Http\Message\RequestInterface;

/**
 * The webhooks API reads verify from the query string, so omitting the argument must send no query
 * at all (the API default), and it must never leak into the JSON body.
 *
 * @internal
 *
 * @coversNothing
 */
class WebhookVerifyQueryTest extends TestCase
{
    #[DataProvider('verifyCases')]
    public function testCreateSendsVerifyAsQuery(?bool $verify, string $expectedQuery): void
    {
        $request = $this->capture(fn (PostmarkClient $c) => $c->createWebhookConfiguration(
            'https://example.com/hook',
            'outbound',
            verify: $verify
        ));

        $this->assertSame('POST', $request->getMethod());
        $this->assertSame('/webhooks', $request->getUri()->getPath());
        $this->assertSame($expectedQuery, $request->getUri()->getQuery());
        $this->assertBodyHasNoVerify($request);
    }

    #[DataProvider('verifyCases')]
    public function testEditSendsVerifyAsQuery(?bool $verify, string $expectedQuery): void
    {
        $request = $this->capture(fn (PostmarkClient $c) => $c->editWebhookConfiguration(
            42,
            'https://example.com/hook',
            verify: $verify
        ));

        $this->assertSame('PUT', $request->getMethod());
        $this->assertSame('/webhooks/42', $request->getUri()->getPath());
        $this->assertSame($expectedQuery, $request->getUri()->getQuery());
        $this->assertBodyHasNoVerify($request);
    }

    /** @return array<string, array{?bool, string}> */
    public static function verifyCases(): array
    {
        return [
            'omitted' => [null, ''],
            'false' => [false, 'verify=false'],
            'true' => [true, 'verify=true'],
        ];
    }

    private function capture(callable $call): RequestInterface
    {
        $history = [];
        $stack = HandlerStack::create(new MockHandler([
            new Response(200, ['content-type' => 'application/json'], json_encode([
                'ID' => 42,
                'Url' => 'https://example.com/hook',
                'MessageStream' => 'outbound',
                'Triggers' => ['Delivery' => ['Enabled' => true]],
            ])),
        ]));
        $stack->push(Middleware::history($history));

        $client = new PostmarkClient('not-applicable');
        $client->setClient(new Client(['handler' => $stack]));
        $call($client);

        $this->assertCount(1, $history);

        return $history[0]['request'];
    }

    private function assertBodyHasNoVerify(RequestInterface $request): void
    {
        $body = json_decode((string) $request->getBody(), true);
        $this->assertIsArray($body);
        $this->assertArrayNotHasKey('Verify', $body);
        $this->assertArrayNotHasKey('verify', $body);
    }
}
