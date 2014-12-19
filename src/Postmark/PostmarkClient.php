<?php

namespace Postmark;

use \Postmark\Model\BatchSendResponse;

class PostmarkClient extends PostmarkClientBase {

	private $server_token = NULL;

	function __construct($server_token) {
		parent::__construct($server_token, 'X-Postmark-Server-Token');
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

		return (object)$this->processRestRequest('POST', '/email', $payload);
	}

	function sendEmailBatch($emailBatch = []) {
	 	return $this->processRestRequest('POST', '/email/batch', $emailBatch);
	}

	function getDeliveryStatistics() {
		return (object)$this->processRestRequest('GET', '/deliverystats');
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
		
		return (object)$this->processRestRequest('GET', '/bounces', $query);
	}

	function getBounce($id){
		return (object)$this->processRestRequest('GET', "/bounces/$id");
	}

	function getBounceDump($id){
		return (object)$this->processRestRequest('GET', "/bounces/$id/dump");
	}

	function activateBounce($id){
		return (object)$this->processRestRequest('PUT', "/bounces/$id/activate");
	}

	function getBounceTags(){
		return $this->processRestRequest('GET', '/bounces/tags');
	}

	function getServer(){
		return (object)$this->processRestRequest('GET', '/server');
	}

	function editServer($name = NULL, $color = NULL, $rawEmailEnabled = NULL, 
		$smtpApiActivated = NULL, $inboundHookUrl = NULL, $bounceHookUrl = NULL, 
		$openHookUrl = NULL, $postFirstOpenOnly = NULL, $trackOpens = NULL, 
		$inboundDomain = NULL, $inboundSpamThreshold = NULL){
	
		$body = [];
		$body["Name"] = $name;
		$body["Color"] = $color;
		$body["RawEmailEnabled"] = $rawEmailEnabled;
		$body["SmtpApiActivated"] = $smtpApiActivated;
		$body["InboundHookUrl"] = $inboundHookUrl;
		$body["BounceHookUrl"] = $bounceHookUrl;
		$body["OpenHookUrl"] = $openHookUrl;
		$body["PostFirstOpenOnly"] = $postFirstOpenOnly;
		$body["TrackOpens"] = $trackOpens;
		$body["InboundDomain"] = $inboundDomain;
		$body["InboundSpamThreshold"] = $inboundSpamThreshold;

		return (object)$this->processRestRequest('PUT', '/server', $body);
	}

	function getOutboundMessages( $recipient = NULL, $fromemail = NULL, $tag = NULL, 
		$subject = NULL, $count = 100, $offset = 0){
		
		$query = [];
		$query["recipient"] = $recipient;
		$query["fromemail"] = $fromemail;
		$query["tag"] = $tag;
		$query["subject"] = $subject;
		$query["count"] = $count;
		$query["offset"] = $offset;
		
		return (object)$this->processRestRequest('GET', '/messages/outbound', $query);
	}

	function getOutboundMessageDetails($id){
		return (object)$this->processRestRequest('GET', "/messages/outbound/$id/details");	
	}

	function getOutboundMessageDump($id){
		return (object)$this->processRestRequest('GET', "/messages/outbound/$id/dump");	
	}

	function getInboundMessages($recipient = NULL, $fromemail = NULL, 
				$tag = NULL, $subject = NULL, $mailboxhash = NULL,
				$status = NULL, $count = 100, $offset = 0){
		
		$query = [];
		$query['recipient'] = $recipient;
		$query['fromemail'] = $fromemail;
		$query['tag'] = $tag;
		$query['subject'] = $subject;
		$query['mailboxhash'] = $mailboxhash;
		$query['count'] = $count;
		$query['offset'] = $offset;
		
		return (object)$this->processRestRequest('GET', '/messages/inbound', $query);
	}

	function getInboundMessageDetails($id){
		return (object)$this->processRestRequest('GET', "/messages/inbound/$id/details");	
	}


	function bypassInboundMessageRules($id){
		return (object)$this->processRestRequest('PUT', "/messages/inbound/$id/bypass");	
	}

	function getOpenStatistics($recipient = NULL, 
		$tag = NULL, $client_name = NULL, $client_company = NULL,$client_family = NULL, 
		$os_name = NULL,$os_family = NULL, $os_company = NULL,$platform = NULL, 
		$country = NULL,$region = NULL, $city = NULL, $count = 100, $offset = 0){

		$query = [];
		$query['count'] = $count;
		$query['offset'] = $offset;
		$query['recipient'] = $recipient;
		$query['tag'] = $tag;
		$query['client_name'] = $client_name;
		$query['client_company'] = $client_company;
		$query['client_family'] = $client_family;
		$query['os_name'] = $os_name;
		$query['os_family'] = $os_family;
		$query['os_company'] = $os_company;
		$query['platform'] = $platform;
		$query['country'] = $country;
		$query['region'] = $region;
		$query['city'] = $city;

		return (object)$this->processRestRequest('GET', '/messages/outbound/opens', $query);	
	}

	function getOpenStatisticsForMessage($id, $count = 100, $offset = 0){
		$query = [];
		
		$query['count'] = $count;
		$query['offset'] = $offset;

		return (object)$this->processRestRequest('GET', "/messages/outbound/opens/$id", $query);
	}

	function getOutboundOverviewStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound', $query);
	}

	function getOutboundSendStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/sends', $query);
	}
	function getOutboundBounceStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/bounces', $query);
	}

	function getOutboundSpamComplaintStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/spam', $query);
	}

	function getOutboundTrackedStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/tracked', $query);
	}


	function getOutboundOpenStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/opens', $query);
	}

	function getOutboundPlatformStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/opens/platforms', $query);
	}

	function getOutboundEmailClientStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/opens/emailclients', $query);
	}

	function getOutboundReadTimeStatistics($tag = NULL, $fromdate = NULL, $todate = NULL){
		$query = [];
		
		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return (object)$this->processRestRequest('GET', '/stats/outbound/opens/readtimes', $query);
	}
}

?>