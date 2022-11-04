<?php

declare(strict_types=1);

namespace Postmark\Models;

use JsonSerializable;
use Postmark\Exception\AttachmentFileCannotBeLoaded;

use function base64_encode;
use function file_exists;
use function file_get_contents;
use function is_readable;

final class PostmarkAttachment implements JsonSerializable
{
    private const DEFAULT_MIMETYPE = 'application/octet-stream';

    private string $mimeType;

    /**
     * @param non-empty-string      $base64EncodedData
     * @param non-empty-string      $attachmentName
     * @param non-empty-string|null $mimeType
     * @param non-empty-string|null $contentId
     */
    private function __construct(
        private string $base64EncodedData,
        private string $attachmentName,
        string|null $mimeType = null,
        private string|null $contentId = null,
    ) {
        $this->mimeType = $mimeType ?: self::DEFAULT_MIMETYPE;
    }

    /**
     * @param non-empty-string      $data
     * @param non-empty-string      $attachmentName
     * @param non-empty-string|null $mimeType
     * @param non-empty-string|null $contentId
     */
    public static function fromRawData(
        string $data,
        string $attachmentName,
        string|null $mimeType = null,
        string|null $contentId = null,
    ): self {
        return new PostmarkAttachment(base64_encode($data), $attachmentName, $mimeType, $contentId);
    }

    /**
     * @param non-empty-string      $base64EncodedData
     * @param non-empty-string      $attachmentName
     * @param non-empty-string|null $mimeType
     * @param non-empty-string|null $contentId
     */
    public static function fromBase64EncodedData(
        string $base64EncodedData,
        string $attachmentName,
        string|null $mimeType = null,
        string|null $contentId = null,
    ): self {
        return new PostmarkAttachment($base64EncodedData, $attachmentName, $mimeType, $contentId);
    }

    /**
     * @param non-empty-string      $filePath
     * @param non-empty-string      $attachmentName
     * @param non-empty-string|null $mimeType
     * @param non-empty-string|null $contentId
     */
    public static function fromFile(
        string $filePath,
        string $attachmentName,
        string|null $mimeType = null,
        string|null $contentId = null,
    ): self {
        if (! file_exists($filePath) || ! is_readable($filePath)) {
            throw AttachmentFileCannotBeLoaded::fromPath($filePath);
        }

        /** @psalm-var non-empty-string $data */
        $data = file_get_contents($filePath);

        return self::fromRawData(
            $data,
            $attachmentName,
            $mimeType,
            $contentId,
        );
    }

    /** @return array<string, string> */
    public function jsonSerialize(): array
    {
        return [
            'Name' => $this->attachmentName,
            'Content' => $this->base64EncodedData,
            'ContentType' => $this->mimeType ?: 'application/octet-stream',
            'ContentId' => $this->contentId ?: $this->attachmentName,
        ];
    }
}
