<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use Postmark\Models\CaseInsensitiveArray;

class CaseInsensitiveArrayTest extends TestCase
{
    public function testValuesCanBeRetrievedInAnyCase(): void
    {
        $data = new CaseInsensitiveArray(['value' => 1]);

        self::assertSame(1, $data['Value']);
        self::assertSame(1, $data['VALUE']);
        self::assertSame(1, $data['VaLUe']);
        self::assertSame(1, $data['value']);
    }

    public function testThatOffsetGetDoesNotIssueANoticeOnPHP81(): void
    {
        $data = new CaseInsensitiveArray(['hey' => 'there']);
        self::assertEquals('there', $data->offsetGet('hey'));
    }

    public function testValuesCanBeMutatedInACaseInsensitiveWay(): void
    {
        $data = new CaseInsensitiveArray(['value' => 1]);
        $data['VALUE'] = 'Foo';

        self::assertSame('Foo', $data['Value']);
        self::assertSame('Foo', $data['VALUE']);
        self::assertSame('Foo', $data['VaLUe']);
        self::assertSame('Foo', $data['value']);
    }

    public function testValuesCanBeAppended(): void
    {
        $data = new CaseInsensitiveArray(['value' => 1]);
        $data[] = 'Foo';

        self::assertSame(1, $data['value']);
        self::assertSame('Foo', $data[0]);
    }

    public function testIterationUsesLowercaseKeys(): void
    {
        $data = new CaseInsensitiveArray(['FOO' => 'bar']);
        foreach ($data as $key => $value) {
            self::assertSame('foo', $key);
            self::assertSame('bar', $value);
        }
    }

    public function testKeysCanBeUnsetInCaseInsensitiveManner(): void
    {
        $data = new CaseInsensitiveArray(['FOO' => 'bar']);
        self::assertArrayHasKey('foo', $data);
        unset($data['fOo']);
        self::assertArrayNotHasKey('foo', $data);
    }
}
