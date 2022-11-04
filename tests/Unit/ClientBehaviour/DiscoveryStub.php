<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\Discovery;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

final class DiscoveryStub
{
    use Discovery;

    public function client(ClientInterface|null $client): ClientInterface
    {
        return self::resolveHttpClient($client);
    }

    public function stream(): StreamFactoryInterface
    {
        return self::resolveStreamFactory();
    }

    public function request(): RequestFactoryInterface
    {
        return self::resolveRequestFactory();
    }

    public function uri(): UriFactoryInterface
    {
        return self::resolveUriFactory();
    }
}
