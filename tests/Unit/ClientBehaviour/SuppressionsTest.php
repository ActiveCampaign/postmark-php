<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\Suppressions;
use Postmark\Models\Suppressions\SuppressionChangeRequest;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/** @see Suppressions */
class SuppressionsTest extends MockClientTestCase
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

    /** @return array<array-key, array{0: string, 1: string|null, 2: string}> */
    public function createAndDeleteSuppressionsDataProvider(): array
    {
        return [
            [
                'createSuppressions',
                null,
                '/message-streams/outbound/suppressions',
            ],
            [
                'deleteSuppressions',
                null,
                '/message-streams/outbound/suppressions/delete',
            ],
            [
                'createSuppressions',
                'other',
                '/message-streams/other/suppressions',
            ],
            [
                'deleteSuppressions',
                'other',
                '/message-streams/other/suppressions/delete',
            ],
        ];
    }

    /**
     * @covers \Postmark\ClientBehaviour\Suppressions::createSuppressions
     * @covers \Postmark\ClientBehaviour\Suppressions::deleteSuppressions
     * @dataProvider createAndDeleteSuppressionsDataProvider
     */
    public function testCreateSuppressionsWithoutList(string $method, ?string $stream, string $expectPath): void
    {
        if ($stream === null) {
            $this->client->$method();
        } else {
            $this->client->$method([], $stream);
        }

        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals($expectPath);
        $this->assertBodyParameterValueEquals('Suppressions', []);
    }

    /**
     * @covers \Postmark\ClientBehaviour\Suppressions::createSuppressions
     * @covers \Postmark\ClientBehaviour\Suppressions::deleteSuppressions
     * @dataProvider createAndDeleteSuppressionsDataProvider
     */
    public function testCreateSuppressionsWithNonEmptySuppressionList(string $method, ?string $stream, string $expectPath): void
    {
        $suppression = new SuppressionChangeRequest('me@mine.com');
        $expect = [
            0 => $suppression->jsonSerialize(),
        ];

        if ($stream === null) {
            $this->client->$method([$suppression]);
        } else {
            $this->client->$method([$suppression], $stream);
        }

        $this->assertLastRequestPathEquals($expectPath);
        $this->assertBodyParameterValueEquals('Suppressions', $expect);
    }

    public function testGetSuppressionsWithNoArguments(): void
    {
        $this->client->getSuppressions();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/message-streams/outbound/suppressions/dump');
        $missing = [
            'SuppressionReason',
            'Origin',
            'FromDate',
            'ToDate',
            'EmailAddress',
        ];

        foreach ($missing as $name) {
            $this->assertQueryParameterIsAbsent($name);
        }
    }

    public function testGetSuppressionsWithArguments(): void
    {
        $this->client->getSuppressions(
            'other',
            'Reason',
            'Origin',
            'FromDate',
            'ToDate',
            'Email',
        );
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/message-streams/other/suppressions/dump');
        $expect = [
            'SuppressionReason' => 'Reason',
            'Origin' => 'Origin',
            'FromDate' => 'FromDate',
            'ToDate' => 'ToDate',
            'EmailAddress' => 'Email',
        ];

        foreach ($expect as $name => $value) {
            $this->assertQueryParameterValueEquals($name, $value);
        }
    }
}
