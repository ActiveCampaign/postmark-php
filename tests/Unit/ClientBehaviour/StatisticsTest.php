<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Postmark\ClientBehaviour\Statistics;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/** @link Statistics */
#[CoversClass(Statistics::class)]
class StatisticsTest extends MockClientTestCase
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

    /** @return array<string, array{0: string, 1:string}> */
    public static function similarFourArgumentStatsMethodProvider(): array
    {
        return [
            'getOutboundOverviewStatistics' => ['getOutboundOverviewStatistics', '/stats/outbound'],
            'getOutboundSendStatistics' => ['getOutboundSendStatistics', '/stats/outbound/sends'],
            'getOutboundBounceStatistics' => ['getOutboundBounceStatistics', '/stats/outbound/bounces'],
            'getOutboundSpamComplaintStatistics' => ['getOutboundSpamComplaintStatistics', '/stats/outbound/spam'],
            'getOutboundTrackedStatistics' => ['getOutboundTrackedStatistics', '/stats/outbound/tracked'],
            'getOutboundOpenStatistics' => ['getOutboundOpenStatistics', '/stats/outbound/opens'],
            'getOutboundPlatformStatistics' => ['getOutboundPlatformStatistics', '/stats/outbound/opens/platforms'],
            'getOutboundEmailClientStatistics' => ['getOutboundEmailClientStatistics', '/stats/outbound/opens/emailclients'],
            'getOutboundClickStatistics' => ['getOutboundClickStatistics', '/stats/outbound/clicks'],
            'getOutboundClickBrowserFamilyStatistics' => ['getOutboundClickBrowserFamilyStatistics', '/stats/outbound/clicks/browserfamilies'],
            'getOutboundClickBrowserPlatformStatistics' => ['getOutboundClickBrowserPlatformStatistics', '/stats/outbound/clicks/platforms'],
            'getOutboundClickLocationStatistics' => ['getOutboundClickLocationStatistics', '/stats/outbound/clicks/location'],
        ];
    }

    #[DataProvider('similarFourArgumentStatsMethodProvider')]
    public function testSimilarFourArgumentStatsMethods(string $method, string $expectedPath): void
    {
        $this->client->$method('T', 'FROM', 'TO', 'stream');
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals($expectedPath);
        $this->assertQueryParameterValueEquals('tag', 'T');
        $this->assertQueryParameterValueEquals('fromdate', 'FROM');
        $this->assertQueryParameterValueEquals('todate', 'TO');
        $this->assertQueryParameterValueEquals('messagestream', 'stream');
    }

    /** @return array<string, array{0: string, 1:string}> */
    public static function similarThreeArgumentStatsMethodsProvider(): array
    {
        return [
            'getOutboundReadTimeStatistics' => ['getOutboundReadTimeStatistics', '/stats/outbound/opens/readtimes'],
        ];
    }

    #[DataProvider('similarThreeArgumentStatsMethodsProvider')]
    public function testSimilarThreeArgumentStatsMethods(string $method, string $expectedPath): void
    {
        $this->client->$method('T', 'FROM', 'TO');
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals($expectedPath);
        $this->assertQueryParameterValueEquals('tag', 'T');
        $this->assertQueryParameterValueEquals('fromdate', 'FROM');
        $this->assertQueryParameterValueEquals('todate', 'TO');
    }

    public function testGetDeliveryStatistics(): void
    {
        $this->client->getDeliveryStatistics();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/deliverystats');
    }

    public function testGetOpenStatistics(): void
    {
        $this->client->getOpenStatistics();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/messages/outbound/opens');
    }

    public function testGetClickStatistics(): void
    {
        $this->client->getClickStatistics();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/messages/outbound/clicks');
    }

    public function testGetOpenStatisticsForMessage(): void
    {
        $this->client->getOpenStatisticsForMessage('my-id', 5, 1);
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/messages/outbound/opens/my-id');
        $this->assertQueryParameterValueEquals('count', '5');
        $this->assertQueryParameterValueEquals('offset', '1');
    }

    public function testGetClickStatisticsForMessage(): void
    {
        $this->client->getClickStatisticsForMessage('my-id', 5, 1);
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/messages/outbound/clicks/my-id');
        $this->assertQueryParameterValueEquals('count', '5');
        $this->assertQueryParameterValueEquals('offset', '1');
    }
}
