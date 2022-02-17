<?php

declare(strict_types=1);

namespace Postmark\Exception;

use RuntimeException;

use function sprintf;

final class AttachmentFileCannotBeLoaded extends RuntimeException implements PostmarkException
{
    /** @param non-empty-string $filePath */
    public static function fromPath(string $filePath): self
    {
        return new self(sprintf(
            'The file at "%s" cannot be loaded. Either because it does not exist, or couldn’t be read',
            $filePath
        ), 0);
    }
}
