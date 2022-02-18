<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\Templates;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/**
 * @see Templates
 */
class TemplatesTest extends MockClientTestCase
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

    /** @return array<string, array{0: string, 1: string|int, 2: string, 3: string}> */
    public function singleStringIdArgumentMethodProvider(): array
    {
        return [
            'deleteTemplate with alias' => ['deleteTemplate', 'some-id', '/templates/some-id', 'DELETE'],
            'getTemplate with alias' => ['getTemplate', 'some-id', '/templates/some-id', 'GET'],
            'deleteTemplate with id' => ['deleteTemplate', 99, '/templates/99', 'DELETE'],
            'getTemplate with id' => ['getTemplate', 44, '/templates/44', 'GET'],
        ];
    }

    /**
     * @param string|int $id
     *
     * @covers \Postmark\ClientBehaviour\Templates::deleteTemplate
     * @covers \Postmark\ClientBehaviour\Templates::getTemplate
     * @dataProvider singleStringIdArgumentMethodProvider
     */
    public function testSingleStringIdMethods(string $method, $id, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($id);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }
}
