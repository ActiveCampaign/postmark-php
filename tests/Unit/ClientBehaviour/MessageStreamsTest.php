<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Postmark\ClientBehaviour\MessageStreams;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/** @see MessageStreams */
#[CoversClass(MessageStreams::class)]
class MessageStreamsTest extends MockClientTestCase
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
            'getMessageStream' => ['getMessageStream', 'some-id', '/message-streams/some-id', 'GET'],
            'archiveMessageStream' => ['archiveMessageStream', 'some-id', '/message-streams/some-id/archive', 'POST'],
            'unarchiveMessageStream' => ['unarchiveMessageStream', 'some-id', '/message-streams/some-id/unarchive', 'POST'],
        ];
    }

    #[DataProvider('singleStringIdArgumentMethodProvider')]
    public function testSingleStringIdMethods(string $method, string $id, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($id);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }

    public function testCreateMessageStream(): void
    {
        $this->client->createMessageStream('my-id', 'type', 'name', 'description');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/message-streams');
        $this->assertBodyParameterValueEquals('ID', 'my-id');
        $this->assertBodyParameterValueEquals('MessageStreamType', 'type');
        $this->assertBodyParameterValueEquals('Name', 'name');
        $this->assertBodyParameterValueEquals('Description', 'description');
    }

    public function testEditMessageStream(): void
    {
        $this->client->editMessageStream('my-id', 'name');
        $this->assertLastRequestMethodWas('PATCH');
        $this->assertLastRequestPathEquals('/message-streams/my-id');
        $this->assertBodyParameterValueEquals('Name', 'name');
        $this->assertBodyParameterIsAbsent('Description');

        $this->client->editMessageStream('my-id', null, 'some thing');
        $this->assertBodyParameterValueEquals('Description', 'some thing');
        $this->assertBodyParameterIsAbsent('Name');
    }

    public function testListMessageStreams(): void
    {
        $this->client->listMessageStreams();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/message-streams');
        $this->assertQueryParameterValueEquals('MessageStreamType', 'All');
        $this->assertQueryParameterValueEquals('IncludeArchivedStreams', 'false');

        $this->client->listMessageStreams('Something', true);
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/message-streams');
        $this->assertQueryParameterValueEquals('MessageStreamType', 'Something');
        $this->assertQueryParameterValueEquals('IncludeArchivedStreams', 'true');
    }
}
