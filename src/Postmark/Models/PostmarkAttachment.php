<?php

namespace Postmark\Models;

class PostmarkAttachment implements \JsonSerializable {

	private $name;
	private $mimeType;
	private $data;
	private $contentId;

	public static function fromRawData($data, $attachmentName, $mimeType = NULL, $contentId = NULL) {
		return new PostmarkAttachment(base64_encode($data), $attachmentName, $mimeType, $contentId);
	}
	
	public static function fromBase64EncodedData($base64EncodedData, $attachmentName, $mimeType = NULL, $contentId = NULL) {
		return new PostmarkAttachment($base64EncodedData, $attachmentName, $mimeType, $contentId);
	}
	
	public static function fromFile($filePath, $attachmentName, $mimeType = NULL, $contentId = NULL) {
		return new PostmarkAttachment(base64_encode(file_get_contents($filePath)), $attachmentName, $mimeType, $contentId);
	}

	function jsonSerialize() {

		$retval = array(
			"Name" => $this->name,
			"Content" => $this->data,
			"ContentType" => $this->mimeType ? $this->mimeType : "application/octet-stream",
			"ContentId" => $this->contentId ? $this->contentId : $this->name
        );

		return $retval;
	}

	private function __construct($base64EncodedData, $attachmentName, $mimeType = "application/octet-stream", $contentId = NULL) {
		$this->name = $attachmentName;
		$this->data = $base64EncodedData;
		$this->mimeType = $mimeType;
		$this->contentId = $contentId;
	}

}