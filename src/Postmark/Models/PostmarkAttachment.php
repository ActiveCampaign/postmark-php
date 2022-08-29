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

    private string $name;
    private string $mimeType;
    private string $data;
    private ?string $contentId;

    /**
     * @param non-empty-string      $base64EncodedData
     * @param non-empty-string      $attachmentName
     * @param non-empty-string|null $mimeType
     * @param non-empty-string|null $contentId
     */
    private function __construct(
        string $base64EncodedData,
        string $attachmentName,
        ?string $mimeType = null,
        ?string $contentId = null
    ) {
        $this->name = $attachmentName;
        $this->data = $base64EncodedData;
        $this->mimeType = $mimeType ?: self::DEFAULT_MIMETYPE;
        $this->contentId = $contentId;
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
        ?string $mimeType = null,
        ?string $contentId = null
    ): self {
        /** @psalm-var non-empty-string $encoded */
        $encoded = base64_encode($data);

        return new PostmarkAttachment($encoded, $attachmentName, $mimeType, $contentId);
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
        ?string $mimeType = null,
        ?string $contentId = null
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
        ?string $mimeType = null,
        ?string $contentId = null
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
            'Name' => $this->name,
            'Content' => $this->data,
            'ContentType' => $this->mimeType ?: 'application/octet-stream',
            'ContentId' => $this->contentId ?: $this->name,
        ];
    }
}
