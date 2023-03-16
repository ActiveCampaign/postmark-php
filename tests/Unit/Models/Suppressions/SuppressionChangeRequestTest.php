<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models\Suppressions;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Postmark\Models\Suppressions\SuppressionChangeRequest;

class SuppressionChangeRequestTest extends TestCase
{
    /** @return array<string, array{0: string|null, 1: string|null}> */
    public static function possibleValueProvider(): array
    {
        return [
            'Null' => [null, null],
            'Empty String' => ['', ''],
            'Non Empty String' => ['not-empty', 'not-empty'],
            'An actual email address' => ['me@example.com', 'me@example.com'],
        ];
    }

    #[DataProvider('possibleValueProvider')]
    public function testWhatGoesInComesOut(string|null $input, string|null $expect): void
    {
        $value = new SuppressionChangeRequest($input);
        self::assertSame($expect, $value->getEmailAddress());
    }

    #[DataProvider('possibleValueProvider')]
    public function testSerializationToTheExpectedValue(string|null $input, string|null $expect): void
    {
        $value = new SuppressionChangeRequest($input);
        self::assertSame(['EmailAddress' => $expect], $value->jsonSerialize());
    }
}
