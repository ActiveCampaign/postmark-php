<?php

namespace Postmark;

use Postmark\Models\DynamicResponseModel as DynamicResponseModel;
use Postmark\PostmarkClientBase as PostmarkClientBase;

class PostmarkClient extends PostmarkClientBase {

	private $server_token = NULL;

	function __construct($server_token) {
		parent::__construct($server_token, 'X-Postmark-Server-Token');
	}

	function sendEmail($from, $to, $subject, $htmlBody = NULL, $textBody = NULL,
		$tag = NULL, $trackOpens = true, $replyTo = NULL, $cc = NULL, $bcc = NULL, $headers = NULL) {

		//TODO: Support attachments.

		$body = [];
		$body['From'] = $from;
		$body['To'] = $to;
		$body['Cc'] = $cc;
		$body['Bcc'] = $bcc;
		$body['Subject'] = $subject;
		$body['HtmlBody'] = $htmlBody;
		$body['TextBody'] = $textBody;
		$body['ReplyTo'] = $replyTo;
		$body['Headers'] = $headers;
		$body['TrackOpens'] = $trackOpen;

		return (object) $this->processRestRequest('POST', '/email', $body);
	}

	function sendEmailBatch($emailBatch = []) {
		return $this->processRestRequest('POST', '/email/batch', $emailBatch);
	}

	function getDeliveryStatistics() {
		return (object) $this->processRestRequest('GET', '/deliverystats');
	}

	function getBounces($type = NULL, $inactive = NULL, $emailFilter = NULL,
		$tag = NULL, $messageID = NULL, $count = 100, $offset = 0) {

		$query = [];
		$query['type'] = $type;
		$query['inactive'] = $inactive;
		$query['emailFilter'] = $emailFilter;
		$query['tag'] = $tag;
		$query['messageID'] = $messageID;
		$query['count'] = $count;
		$query['offset'] = $offset;

		return (object) $this->processRestRequest('GET', '/bounces', $query);
	}

	function getBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id"));
	}

	function getBounceDump($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id/dump"));
	}

	function activateBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/bounces/$id/activate"));
	}

	function getBounceTags() {
		return $this->processRestRequest('GET', '/bounces/tags');
	}

	function getServer() {
		return new DynamicResponseModel($this->processRestRequest('GET', '/server'));
	}

	function editServer($name = NULL, $color = NULL, $rawEmailEnabled = NULL,
		$smtpApiActivated = NULL, $inboundHookUrl = NULL, $bounceHookUrl = NULL,
		$openHookUrl = NULL, $postFirstOpenOnly = NULL, $trackOpens = NULL,
		$inboundDomain = NULL, $inboundSpamThreshold = NULL) {

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

		return new DynamicResponseModel($this->processRestRequest('PUT', '/server', $body));
	}

	function getOutboundMessages($recipient = NULL, $fromemail = NULL, $tag = NULL,
		$subject = NULL, $count = 100, $offset = 0) {

		$query = [];
		$query["recipient"] = $recipient;
		$query["fromemail"] = $fromemail;
		$query["tag"] = $tag;
		$query["subject"] = $subject;
		$query["count"] = $count;
		$query["offset"] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound', $query));
	}

	function getOutboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/details"));
	}

	function getOutboundMessageDump($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/dump"));
	}

	function getInboundMessages($recipient = NULL, $fromemail = NULL,
		$tag = NULL, $subject = NULL, $mailboxhash = NULL,
		$status = NULL, $count = 100, $offset = 0) {

		$query = [];
		$query['recipient'] = $recipient;
		$query['fromemail'] = $fromemail;
		$query['tag'] = $tag;
		$query['subject'] = $subject;
		$query['mailboxhash'] = $mailboxhash;
		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/messages/inbound', $query));
	}

	function getInboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/inbound/$id/details"));
	}

	function bypassInboundMessageRules($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/messages/inbound/$id/bypass"));
	}

	function getOpenStatistics($recipient = NULL,
		$tag = NULL, $client_name = NULL, $client_company = NULL, $client_family = NULL,
		$os_name = NULL, $os_family = NULL, $os_company = NULL, $platform = NULL,
		$country = NULL, $region = NULL, $city = NULL, $count = 100, $offset = 0) {

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

		return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound/opens', $query));
	}

	function getOpenStatisticsForMessage($id, $count = 100, $offset = 0) {
		$query = [];

		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/opens/$id", $query));
	}

	function getOutboundOverviewStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound', $query));
	}

	function getOutboundSendStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/sends', $query));
	}
	function getOutboundBounceStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/bounces', $query));
	}

	function getOutboundSpamComplaintStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/spam', $query));
	}

	function getOutboundTrackedStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/tracked', $query));
	}

	function getOutboundOpenStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens', $query));
	}

	function getOutboundPlatformStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/platforms', $query));
	}

	function getOutboundEmailClientStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/emailclients', $query));
	}

	function getOutboundReadTimeStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/readtimes', $query));
	}

	function createTagTrigger($matchName, $trackOpens = true) {
		$body = [];
		$body["MatchName"] = $matchName;
		$body["TrackOpens"] = $trackOpens;

		return new DynamicResponseModel($this->processRestRequest('POST', '/triggers/tags', $body));
	}

	function deleteTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/tags/$id"));
	}

	function searchTagTriggers($count = 100, $offset = 0, $match_name = NULL) {
		$query = [];

		$query["count"] = $count;
		$query["offset"] = $offset;
		$query["match_name"] = $match_name;

		return new DynamicResponseModel($this->processRestRequest('GET', '/triggers/tags', $query));
	}

	function editTagTrigger($id, $matchName, $trackOpens = true) {
		$body = [];
		$body["MatchName"] = $matchName;
		$body["TrackOpens"] = $trackOpens;

		return new DynamicResponseModel($this->processRestRequest('PUT', "/triggers/tags/$id", $body));
	}

	function getTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/triggers/tags/$id"));
	}

	function createInboundRuleTrigger($rule) {
		$body = [];
		$body["Rule"] = $rule;

		return new DynamicResponseModel($this->processRestRequest('POST', '/triggers/inboundrules', $body));
	}

	function listInboundRuleTriggers($count = 100, $offset = 0) {
		$query = [];

		$query["count"] = $count;
		$query["offset"] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/triggers/inboundrules', $query));
	}

	function deleteInboundRuleTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/inboundrules/$id"));
	}
}

?>