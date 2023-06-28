<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Http\Client\Curl\Client;
use Http\Discovery\ClassDiscovery;
use PHPUnit\Framework\TestCase;
use Postmark\Exception\DiscoveryFailure;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

class DiscoveryIntegrationTest extends TestCase
{
    private DiscoveryStub $stub;
    /** @var array<array-key, string> */
    private static array $strategyBackup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stub = new DiscoveryStub();

        self::$strategyBackup = ClassDiscovery::getStrategies();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        ClassDiscovery::setStrategies(self::$strategyBackup);
    }

    public function testAClientCanBeDiscovered(): void
    {
        self::assertInstanceOf(ClientInterface::class, $this->stub->client(null));
    }

    public function testStreamFactoryCanBeDiscovered(): void
    {
        self::assertInstanceOf(StreamFactoryInterface::class, $this->stub->stream());
    }

    public function testUriFactoryCanBeDiscovered(): void
    {
        self::assertInstanceOf(UriFactoryInterface::class, $this->stub->uri());
    }

    public function testRequestFactoryCanBeDiscovered(): void
    {
        self::assertInstanceOf(RequestFactoryInterface::class, $this->stub->request());
    }

    public function testTheSameClientIsReturnedWhenGivenAsAnArgument(): void
    {
        $client = new Client();
        self::assertSame($client, $this->stub->client($client));
    }

    public function testExceptionThrownWhenClientCannotBeDiscovered(): void
    {
        ClassDiscovery::setStrategies([]);
        $this->expectException(DiscoveryFailure::class);
        $this->expectExceptionMessage('A PSR-18 HTTP Client could not be discovered');
        $this->stub->client(null);
    }

    public function testExceptionThrownWhenStreamFactoryCannotBeDiscovered(): void
    {
        ClassDiscovery::setStrategies([]);
        $this->expectException(DiscoveryFailure::class);
        $this->expectExceptionMessage('A PSR-17 Stream Factory implementation');
        $this->stub->stream();
    }

    public function testExceptionThrownWhenUriFactoryCannotBeDiscovered(): void
    {
        ClassDiscovery::setStrategies([]);
        $this->expectException(DiscoveryFailure::class);
        $this->expectExceptionMessage('A PSR-17 URI Factory implementation');
        $this->stub->uri();
    }

    public function testExceptionThrownWhenRequestFactoryCannotBeDiscovered(): void
    {
        ClassDiscovery::setStrategies([]);
        $this->expectException(DiscoveryFailure::class);
        $this->expectExceptionMessage('A PSR-17 Request Factory implementation');
        $this->stub->request();
    }
}
