<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\Bounces;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/**
 * @link Bounces
 */
class BouncesTest extends MockClientTestCase
{
    private PostmarkClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $client = new PostmarkClient('token', $this->mockClient);
        $this->client = $client->withBaseUri('https://example.com');
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
    }

    /** @return array<string, array{0: string, 1: string, 2: string, 3: string}> */
    public function messageRelatedMethodProvider(): array
    {
        return [
            'getBounce' => ['getBounce', 'some-id', '/bounces/some-id', 'GET'],
            'getBounceDump' => ['getBounceDump', 'some-id', '/bounces/some-id/dump', 'GET'],
            'activateBounce' => ['activateBounce', 'some-id', '/bounces/some-id/activate', 'PUT'],
        ];
    }

    /**
     * @covers \Postmark\ClientBehaviour\Bounces::getBounce
     * @covers \Postmark\ClientBehaviour\Bounces::getBounceDump
     * @covers \Postmark\ClientBehaviour\Bounces::activateBounce
     * @dataProvider messageRelatedMethodProvider
     */
    public function testMessageRelatedMethods(string $method, string $messageId, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($messageId);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }

    public function testGetBouncesWithoutParameters(): void
    {
        $this->client->getBounces();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/bounces');

        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');

        $this->assertQueryParameterIsAbsent('type');
        $this->assertQueryParameterIsAbsent('inactive');
        $this->assertQueryParameterIsAbsent('emailFilter');
        $this->assertQueryParameterIsAbsent('tag');
        $this->assertQueryParameterIsAbsent('messageID');
        $this->assertQueryParameterIsAbsent('fromdate');
        $this->assertQueryParameterIsAbsent('todate');
        $this->assertQueryParameterIsAbsent('messagestream');
    }

    public function testGetBouncesWithParameters(): void
    {
        $this->client->getBounces(
            42,
            2,
            'Type',
            false,
            'me@mine.com',
            'some-tag',
            'msg-id',
            'FROM',
            'TO',
            'Stream'
        );

        $this->assertQueryParameterValueEquals('count', '42');
        $this->assertQueryParameterValueEquals('offset', '2');
        $this->assertQueryParameterValueEquals('type', 'Type');
        $this->assertQueryParameterValueEquals('inactive', 'false');
        $this->assertQueryParameterValueEquals('emailFilter', 'me@mine.com');
        $this->assertQueryParameterValueEquals('tag', 'some-tag');
        $this->assertQueryParameterValueEquals('messageID', 'msg-id');
        $this->assertQueryParameterValueEquals('fromdate', 'FROM');
        $this->assertQueryParameterValueEquals('todate', 'TO');
        $this->assertQueryParameterValueEquals('messagestream', 'Stream');
    }
}
