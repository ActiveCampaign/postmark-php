<?php

namespace Postmark\Models;

use JsonSerializable;
use ReturnTypeWillChange;

class PostmarkAttachment implements JsonSerializable
{
    private $name;
    private $mimeType;
    private $data;
    private $contentId;

    private function __construct($base64EncodedData, $attachmentName, $mimeType = 'application/octet-stream', ?string $contentId = null)
    {
        $this->name = $attachmentName;
        $this->data = $base64EncodedData;
        $this->mimeType = $mimeType;
        $this->contentId = $contentId;
    }

    public static function fromRawData(string $data, string $attachmentName, ?string $mimeType = null, ?string $contentId = null): PostmarkAttachment
    {
        return new PostmarkAttachment(base64_encode($data), $attachmentName, $mimeType, $contentId);
    }

    public static function fromBase64EncodedData(string $base64EncodedData, string $attachmentName, ?string $mimeType = null, ?string $contentId = null): PostmarkAttachment
    {
        return new PostmarkAttachment($base64EncodedData, $attachmentName, $mimeType, $contentId);
    }

    public static function fromFile(string $filePath, string $attachmentName, ?string $mimeType = null, ?string $contentId = null): PostmarkAttachment
    {
        return new PostmarkAttachment(base64_encode(file_get_contents($filePath)), $attachmentName, $mimeType, $contentId);
    }

    #[ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'Name' => $this->name,
            'Content' => $this->data,
            'ContentType' => $this->mimeType ? $this->mimeType : 'application/octet-stream',
            'ContentId' => $this->contentId ? $this->contentId : $this->name,
        ];
    }
}
