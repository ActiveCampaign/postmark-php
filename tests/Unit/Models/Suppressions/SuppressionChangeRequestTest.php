<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models\Suppressions;

use PHPUnit\Framework\TestCase;
use Postmark\Models\Suppressions\SuppressionChangeRequest;

class SuppressionChangeRequestTest extends TestCase
{
    /** @return array<string, array{0: string|null, 1: string|null}> */
    public function possibleValueProvider(): array
    {
        return [
            'Null' => [null, null],
            'Empty String' => ['', ''],
            'Non Empty String' => ['not-empty', 'not-empty'],
            'An actual email address' => ['me@example.com', 'me@example.com'],
        ];
    }

    /** @dataProvider possibleValueProvider */
    public function testWhatGoesInComesOut(?string $input, ?string $expect): void
    {
        $value = new SuppressionChangeRequest($input);
        self::assertSame($expect, $value->getEmailAddress());
    }

    /** @dataProvider possibleValueProvider */
    public function testSerializationToTheExpectedValue(?string $input, ?string $expect): void
    {
        $value = new SuppressionChangeRequest($input);
        self::assertSame(['EmailAddress' => $expect], $value->jsonSerialize());
    }
}
