<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Postmark\Models\DynamicResponseModel;

class DynamicResponseModelTest extends TestCase
{
    public function testThatNestedArraysAreConvertedToInstances(): void
    {
        $value = new DynamicResponseModel(['foo' => ['baz' => 'bat']]);

        $nested = $value['foo'];
        self::assertInstanceOf(DynamicResponseModel::class, $nested);
        self::assertSame('bat', $nested['baz']);
    }

    public function testThatValuesCanBeRetrievedWithPropertyAccess(): void
    {
        $value = new DynamicResponseModel(['foo' => ['baz' => 'bat']]);

        /** @psalm-suppress MixedPropertyFetch */
        self::assertSame('bat', $value->foo->baz);
    }

    public function testThatPropertyAccessIsCaseInsensitive(): void
    {
        $value = new DynamicResponseModel(['foo' => 'bar']);

        self::assertSame('bar', $value->foo);
        self::assertSame('bar', $value->FOO);
        self::assertSame('bar', $value->foO);
        self::assertNull($value->nuts);
    }
}
