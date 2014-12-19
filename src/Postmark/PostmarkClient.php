<?php

namespace Postmark;

use \Postmark\Model\BatchSendResponse;

class PostmarkClient extends PostmarkClientBase {

	private $server_token = NULL;

	function __construct($server_token) {
		parent::__construct($server_token, "X-Postmark-Server-Token");
	}

	function sendEmail($from, $to, $subject, $htmlBody = NULL, $textBody = NULL,
		$tag = NULL, $trackOpens = true, $replyTo = NULL, $cc = NULL, $bcc = NULL, $headers = NULL) {

		//TODO: Support attachments.

		$payload = [
			'From' => $from,
			'To' => $to,
			'Cc' => $cc,
			'Bcc' => $bcc,
			'Subject' => $subject,
			'HtmlBody' => $htmlBody,
			'TextBody' => $textBody,
			'ReplyTo' => $replyTo,
			'Headers' => $headers,
			'TrackOpens' => $trackOpens
		];

		return (object)$this->processRestRequest("POST", "/email", $payload);
	}

	function sendEmailBatch($emailBatch = []) {
	 	return $this->processRestRequest("POST", "/email/batch", $emailBatch);
	}

	function getDeliveryStatistics() {
		return (object)$this->processRestRequest("GET", "/deliverystats");
	}

	function getBounces($type = NULL, $inactive = NULL, $emailFilter = NULL, 
		$tag = NULL, $messageID = NULL, $count = 100, $offset = 0){

		$query = [
			'type' => $type,
			'inactive' => $inactive,
			'emailFilter' => $emailFilter,
			'tag' => $tag,
			'messageID' => $messageID,
			'count' => $count,
			'offset' => $offset
		];
		
		return (object)$this->processRestRequest("GET", "/bounces", $query);
	}

	function getBounce($id){
		return (object)$this->processRestRequest("GET", "/bounces/$id");
	}

	function getBounceDump($id){
		return (object)$this->processRestRequest("GET", "/bounces/$id/dump");
	}
}

?>