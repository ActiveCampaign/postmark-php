<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Postmark\ClientBehaviour\InboundMessages;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/** @see InboundMessages */
#[CoversClass(InboundMessages::class)]
class InboundMessagesTest extends MockClientTestCase
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
    public static function singleStringIdArgumentMethodProvider(): array
    {
        return [
            'getInboundMessageDetails' => ['getInboundMessageDetails', 'some-id', '/messages/inbound/some-id/details', 'GET'],
            'bypassInboundMessageRules' => ['bypassInboundMessageRules', 'some-id', '/messages/inbound/some-id/bypass', 'PUT'],
            'retryInboundMessageHook' => ['retryInboundMessageHook', 'some-id', '/messages/inbound/some-id/retry', 'PUT'],
        ];
    }

    #[DataProvider('singleStringIdArgumentMethodProvider')]
    public function testSingleStringIdMethods(string $method, string $id, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($id);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }

    public function testDeleteInboundRuleTrigger(): void
    {
        $this->client->deleteInboundRuleTrigger(99);
        $this->assertLastRequestMethodWas('DELETE');
        $this->assertLastRequestPathEquals('/triggers/inboundrules/99');
    }

    public function testGetInboundMessagesWithoutParameters(): void
    {
        $this->client->getInboundMessages();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/messages/inbound');
        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');

        $otherParams = [
            'recipient',
            'fromemail',
            'tag',
            'subject',
            'mailboxhash',
            'status',
            'fromdate',
            'todate',
        ];

        foreach ($otherParams as $name) {
            $this->assertQueryParameterIsAbsent($name);
        }
    }

    public function testGetInboundMessagesWithParameters(): void
    {
        $this->client->getInboundMessages(
            42,
            2,
            'Recipient',
            'From',
            'Tag',
            'Subject',
            'Hash',
            'Status',
            'FromDate',
            'ToDate',
        );

        $expect = [
            'count' => '42',
            'offset' => '2',
            'recipient' => 'Recipient',
            'fromemail' => 'From',
            'tag' => 'Tag',
            'subject' => 'Subject',
            'mailboxhash' => 'Hash',
            'status' => 'Status',
            'fromdate' => 'FromDate',
            'todate' => 'ToDate',
        ];
        foreach ($expect as $name => $value) {
            $this->assertQueryParameterValueEquals($name, $value);
        }
    }

    public function testCreateInboundRules(): void
    {
        $this->client->createInboundRuleTrigger('some-rule');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/triggers/inboundrules');
        $this->assertBodyParameterValueEquals('Rule', 'some-rule');
    }

    public function testListInboundRuleTriggers(): void
    {
        $this->client->listInboundRuleTriggers();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/triggers/inboundrules');
        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');

        $this->client->listInboundRuleTriggers(42, 5);
        $this->assertQueryParameterValueEquals('count', '42');
        $this->assertQueryParameterValueEquals('offset', '5');
    }
}
