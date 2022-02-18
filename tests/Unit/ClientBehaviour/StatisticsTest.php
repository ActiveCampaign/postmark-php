<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\Statistics;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/**
 * @link Statistics
 */
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
    public function similarFourArgumentStatsMethodProvider(): array
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

    /**
     * @covers \Postmark\ClientBehaviour\Statistics::stats
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundOverviewStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundSendStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundBounceStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundSpamComplaintStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundTrackedStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundOpenStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundPlatformStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundEmailClientStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundClickStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundClickBrowserFamilyStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundClickBrowserPlatformStatistics
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundClickLocationStatistics
     * @dataProvider similarFourArgumentStatsMethodProvider
     */
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
    public function similarThreeArgumentStatsMethodsProvider(): array
    {
        return [
            'getOutboundReadTimeStatistics' => ['getOutboundReadTimeStatistics', '/stats/outbound/opens/readtimes'],
        ];
    }

    /**
     * @covers \Postmark\ClientBehaviour\Statistics::getOutboundReadTimeStatistics
     * @dataProvider similarThreeArgumentStatsMethodsProvider
     */
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
