<?php

declare(strict_types=1);

namespace Postmark\Exception;

use RuntimeException;
use Throwable;

final class DiscoveryFailure extends RuntimeException implements PostmarkException
{
    public static function clientDiscoveryFailed(Throwable|null $previous = null): self
    {
        return new self(
            'A PSR-18 HTTP Client could not be discovered. Make sure that you install one with composer, '
            . 'for example: `composer require php-http/curl-client`',
            0,
            $previous,
        );
    }

    public static function requestFactoryDiscoveryFailed(Throwable|null $previous = null): self
    {
        return new self(
            'A PSR-17 Request Factory implementation could not be found. Please install one first, '
            . 'for example: `composer require laminas/laminas-diactoros`',
            0,
            $previous,
        );
    }

    public static function uriFactoryDiscoveryFailed(Throwable|null $previous): self
    {
        return new self(
            'A PSR-17 URI Factory implementation could not be found. Please install one first, '
            . 'for example: `composer require laminas/laminas-diactoros`',
            0,
            $previous,
        );
    }

    public static function streamFactoryDiscoveryFailed(Throwable|null $previous): self
    {
        return new self(
            'A PSR-17 Stream Factory implementation could not be found. Please install one first, '
            . 'for example: `composer require laminas/laminas-diactoros`',
            0,
            $previous,
        );
    }
}
