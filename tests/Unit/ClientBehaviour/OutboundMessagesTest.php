<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\OutboundMessages;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

use function array_keys;

/** @see OutboundMessages */
class OutboundMessagesTest extends MockClientTestCase
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
    public function singleStringIdArgumentMethodProvider(): array
    {
        return [
            'getOutboundMessageDetails' => ['getOutboundMessageDetails', 'some-id', '/messages/outbound/some-id/details', 'GET'],
            'getOutboundMessageDump' => ['getOutboundMessageDump', 'some-id', '/messages/outbound/some-id/dump', 'GET'],
        ];
    }

    /**
     * @covers \Postmark\ClientBehaviour\OutboundMessages::getOutboundMessageDetails
     * @covers \Postmark\ClientBehaviour\OutboundMessages::getOutboundMessageDump
     * @dataProvider singleStringIdArgumentMethodProvider
     */
    public function testSingleStringIdMethods(string $method, string $id, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($id);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }

    public function testGetOutboundMessagesWithoutArguments(): void
    {
        $this->client->getOutboundMessages();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/messages/outbound');
        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');
        $this->assertQueryParameterValueEquals('messagestream', 'outbound');

        $this->assertQueryParameterIsAbsent('recipient');
        $this->assertQueryParameterIsAbsent('fromemail');
        $this->assertQueryParameterIsAbsent('tag');
        $this->assertQueryParameterIsAbsent('subject');
        $this->assertQueryParameterIsAbsent('status');
        $this->assertQueryParameterIsAbsent('fromdate');
        $this->assertQueryParameterIsAbsent('todate');
    }

    public function testGetOutboundMessagesWithArguments(): void
    {
        $this->client->getOutboundMessages(
            42,
            1,
            'Recipient',
            'From',
            'Tag',
            'Subject',
            'Status',
            'FromDate',
            'ToDate',
            null,
            'Stream',
        );
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/messages/outbound');
        $this->assertQueryParameterValueEquals('count', '42');
        $this->assertQueryParameterValueEquals('offset', '1');
        $this->assertQueryParameterValueEquals('messagestream', 'Stream');

        $this->assertQueryParameterValueEquals('recipient', 'Recipient');
        $this->assertQueryParameterValueEquals('fromemail', 'From');
        $this->assertQueryParameterValueEquals('tag', 'Tag');
        $this->assertQueryParameterValueEquals('subject', 'Subject');
        $this->assertQueryParameterValueEquals('status', 'Status');
        $this->assertQueryParameterValueEquals('fromdate', 'FromDate');
        $this->assertQueryParameterValueEquals('todate', 'ToDate');
    }

    public function testMetadataQueryIsFlattenedAndPrefixed(): void
    {
        $meta = [
            'some' => 'thing',
            'thing' => 'some',
        ];
        $this->client->getOutboundMessages(100, 0, null, null, null, null, null, null, null, $meta);
        $this->assertQueryParameterValueEquals('metadata_some', 'thing');
        $this->assertQueryParameterValueEquals('metadata_thing', 'some');
    }

    public function testEmptyMetadataIsIgnored(): void
    {
        $meta = [
            'some' => '',
            'thing' => null,
        ];
        /** @psalm-suppress InvalidArgument */
        $this->client->getOutboundMessages(100, 0, null, null, null, null, null, null, null, $meta);
        $this->assertQueryParameterIsAbsent('metadata_some');
        $this->assertQueryParameterIsAbsent('metadata_thing');
    }

    public function testIntegerIndexedMetadataIsIgnored(): void
    {
        $meta = [
            0 => 'foo',
            1 => 'bar',
        ];
        /** @psalm-suppress InvalidArgument */
        $this->client->getOutboundMessages(100, 0, null, null, null, null, null, null, null, $meta);
        foreach (array_keys($this->queryParams()) as $key) {
            self::assertIsString($key);
            self::assertStringStartsNotWith('metadata', $key);
        }
    }
}
