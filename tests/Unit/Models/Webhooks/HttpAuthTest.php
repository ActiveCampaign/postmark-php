<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models\Webhooks;

use PHPUnit\Framework\TestCase;
use Postmark\Models\Webhooks\HttpAuth;

class HttpAuthTest extends TestCase
{
    public function testGivenValuesCanBeNull(): void
    {
        $value = new HttpAuth();
        self::assertNull($value->getPassword());
        self::assertNull($value->getUsername());
    }

    public function testThatSerialisingReturnsExpectedKeys(): void
    {
        $value = (new HttpAuth())->jsonSerialize();
        self::assertArrayHasKey('Username', $value);
        self::assertArrayHasKey('Password', $value);
    }

    public function testThatStringValuesAreReturnedAsIs(): void
    {
        $value = new HttpAuth('user', 'pass');
        self::assertSame('user', $value->getUsername());
        self::assertSame('pass', $value->getPassword());
        self::assertSame(['Username' => 'user', 'Password' => 'pass'], $value->jsonSerialize());
    }
}
