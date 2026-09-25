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

    /**
     * @throws \RuntimeException if the file cannot be read
     */
    public static function fromFile(string $filePath, string $attachmentName, ?string $mimeType = null, ?string $contentId = null): PostmarkAttachment
    {
        // file_get_contents() returns false on failure and base64_encode(false) is "",
        // so an unreadable path previously produced a silently empty attachment that
        // still went out with the message.
        //
        // The @ is kept so a consumer's error handler does not turn a warning into an exception before
        // the RuntimeException below can be thrown — but the suppressed reason is recovered and
        // included, because "missing" / "permission denied" / "failed stream wrapper" are a one-minute
        // fix and a support ticket respectively.
        $contents = @file_get_contents($filePath);

        if (false === $contents) {
            $reason = error_get_last()['message'] ?? null;

            throw new \RuntimeException(sprintf(
                'Unable to read attachment file "%s".%s',
                $filePath,
                null === $reason ? '' : ' ' . $reason
            ));
        }

        return new PostmarkAttachment(base64_encode($contents), $attachmentName, $mimeType, $contentId);
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
