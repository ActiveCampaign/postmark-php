<?php

declare(strict_types=1);

namespace Postmark\Exception;

use InvalidArgumentException;

use function sprintf;

final class InvalidRequestMethod extends InvalidArgumentException implements PostmarkException
{
    public static function with(string $method): self
    {
        return new self(sprintf(
            'The request method "%s" is invalid',
            $method,
        ));
    }
}
