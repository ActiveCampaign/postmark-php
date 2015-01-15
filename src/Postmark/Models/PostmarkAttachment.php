<?php

namespace Postmark\Models;

class PostmarkAttachment implements \JsonSerializable {

	private $name;
	private $mimeType;
	private $clientId;
	private $data;

	public static function fromRawData($data, $attachmentName, $mimeType = NULL, $clientId = NULL) {
		return new PostmarkAttachment(base64_encode($data), $attachmentName, $mimeType, $clientId);
	}

	public static function fromStream($stream, $attachmentName, $mimeType = NULL, $clientId = NULL) {

	}

	public static function fromFile($filePath, $attachmentName, $mimeType = NULL, $clientId = NULL) {

	}

	function jsonSerialize() {

		$retval = [
			"Name" => $this->name,
			"Content" => $this->data,
			"ContentType" => $this->mimeType ?: "application/octet-stream",
		];

		if ($this->clientId != NULL) {
			$retval["ClientId"] = $this->clientId;
		}

		return $retval;
	}

	private function __construct($base64EncodedData, $attachmentName, $mimeType = "application/octet-stream", $clientId = NULL) {
		$this->name = $attachmentName;
		$this->data = $base64EncodedData;
		$this->mimeType = $mimeType;
		$this->clientId = $clientId;
	}

}