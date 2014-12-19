<?php

namespace Postmark;

class PostmarkClient extends PostmarkClientBase {

	private $server_token = NULL;

	function __construct($server_token) {
		parent::__construct($server_token, "X-Postmark-Server-Token");
	}

	function sendEmail($from, $to, $subject, $htmlBody, $textBody) {
		$payload = [
			'From' => $from,
			'To' => $to,
			'Subject' => $subject,
			'HtmlBody' => $htmlBody,
			'textBody' => $textBody,
		];

		return $this->processRestRequest("POST", "/email", $payload, NULL);
	}

	function getDeliveryStatistics() {
		return $this->processRestRequest("GET", "/deliverystats", NULL, NULL);
	}
}

?>