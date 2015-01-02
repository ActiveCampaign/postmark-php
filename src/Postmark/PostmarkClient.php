<?php

namespace Postmark;

use Postmark\Models\DynamicResponseModel as DynamicResponseModel;
use Postmark\PostmarkClientBase as PostmarkClientBase;

/**
 * PostmarkClient provides the main functionality used to send an analyze email on a "per-Server"
 * basis. If you'd like to manage "Account-wide" configuration, see the PostmarkAdminClient.
 */
class PostmarkClient extends PostmarkClientBase {

	private $server_token = NULL;

	/**
	 * Create a new PostmarkClient.
	 * @param string $server_token is the "Server" you'd like to use to send/receive email from.
	 */
	function __construct($server_token) {
		parent::__construct($server_token, 'X-Postmark-Server-Token');
	}

	/**
	 * Send an email.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  boolean
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  array
	 * @param  array
	 * @return DynamicResponseModel
	 */
	function sendEmail($from, $to, $subject, $htmlBody = NULL, $textBody = NULL,
		$tag = NULL, $trackOpens = true, $replyTo = NULL, $cc = NULL, $bcc = NULL, $headers = NULL, $attachments = NULL) {

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

		if ($attachments != NULL) {
			//TODO: Support attachments.
			throw new Exception('Attachment support has not yet been included.', 1);

		}
		return new DynamicResponseModel($this->processRestRequest('POST', '/email', $body));
	}

	/**
	 * Send multiple emails as a batch
	 * @param  array
	 * @return DynamicResponseModel
	 */
	function sendEmailBatch($emailBatch = []) {
		return new DynamicResponseModel($this->processRestRequest('POST', '/email/batch', $emailBatch));
	}

	/**
	 * Get an overview of the delivery statistics for all email that has been sent through this Server.
	 * @return DynamicResponseModel
	 */
	function getDeliveryStatistics() {
		return new DynamicResponseModel($this->processRestRequest('GET', '/deliverystats'));
	}

	/**
	 * [getBounces description]
	 * @param  string
	 * @param  bool
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  integer
	 * @param  integer
	 * @return DynamicResponseModel
	 */
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

		return new DynamicResponseModel($this->processRestRequest('GET', '/bounces', $query));
	}

	/**
	 * Locate information on a specific email bounce.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function getBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id"));
	}

	/**
	 * Get a "dump" for a specific bounce.
	 * @param  integer
	 * @return string
	 */
	function getBounceDump($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id/dump"));
	}

	/**
	 * Cause the email address associated with a Bounce to be reactivated.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function activateBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/bounces/$id/activate"));
	}

	/**
	 * Get the list of tags associated with messages that have bounced.
	 * @return array
	 */
	function getBounceTags() {
		return $this->processRestRequest('GET', '/bounces/tags');
	}

	/**
	 * Get the settings for the server associated with this PostmarkClient instance
	 * (defined by the $server_token you passed when instantiating this client)
	 * @return DynamicResponseModel
	 */
	function getServer() {
		return new DynamicResponseModel($this->processRestRequest('GET', '/server'));
	}

	/**
	 * Modify the associated Server. Any parameters passed with NULL will be
	 * ignored (their existing values will not be modified).
	 * @param  string
	 * @param  string
	 * @param  bool
	 * @param  bool
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  bool
	 * @param  bool
	 * @param  string
	 * @param  integer
	 * @return DynamicResponseModel
	 */
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

	/**
	 * Search messages that have been sent using this Server.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  integer
	 * @param  integer
	 * @return DynamicResponseModel
	 */
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

	/**
	 * Get information related to a specific sent message.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function getOutboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/details"));
	}

	/**
	 * Get the raw content for a message that was sent.
	 * @param  integer
	 * @return string
	 */
	function getOutboundMessageDump($id) {
		return $this->processRestRequest('GET', "/messages/outbound/$id/dump")['Body'];
	}

	/**
	 * Get messages sent to the inbound email address associated with this Server.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  integer
	 * @param  integer
	 * @return DynamicResponseModel
	 */
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

	/**
	 * Get details for a specific inbound message.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function getInboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/inbound/$id/details"));
	}

	/**
	 * Allow an inbound message to be processed, even though the filtering rules would normally
	 * prevent it from being processed.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function bypassInboundMessageRules($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/messages/inbound/$id/bypass"));
	}

	/**
	 * Get statistics for tracked messages, optionally filtering by various open event properties.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  string
	 * @param  integer
	 * @param  integer
	 * @return DynamicResponseModel
	 */
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

	/**
	 * Get information about individual opens for a sent message.
	 * @param  integer
	 * @param  integer
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function getOpenStatisticsForMessage($id, $count = 100, $offset = 0) {
		$query = [];

		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/opens/$id", $query));
	}

	/**
	 * Get an overview of the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundOverviewStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound', $query));
	}

	/**
	 * Get send statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundSendStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/sends', $query));
	}

	/**
	 * Get bounce statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundBounceStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/bounces', $query));
	}

	/**
	 * Get SPAM complaint statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundSpamComplaintStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/spam', $query));
	}

	/**
	 * Get bounce statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundTrackedStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/tracked', $query));
	}

	/**
	 * Get open statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundOpenStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens', $query));
	}

	/**
	 * Get platform statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundPlatformStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/platforms', $query));
	}

	/**
	 * Get email client statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundEmailClientStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/emailclients', $query));
	}

	/**
	 * Get reading times for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 * @param  string
	 * @param  string $fromdate must be of the format 'YYYY-MM-DD'
	 * @param  string $todate must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
	 */
	function getOutboundReadTimeStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = [];

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/readtimes', $query));
	}

	/**
	 * Create a Tag Trigger.
	 * @param  string
	 * @param  boolean
	 * @return DynamicResponseModel
	 */
	function createTagTrigger($matchName, $trackOpens = true) {
		$body = [];
		$body["MatchName"] = $matchName;
		$body["TrackOpens"] = $trackOpens;

		return new DynamicResponseModel($this->processRestRequest('POST', '/triggers/tags', $body));
	}

	/**
	 * Delete a Tag Trigger with the given ID.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function deleteTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/tags/$id"));
	}

	/**
	 * Locate Tag Triggers matching the filter criteria.
	 * @param  integer
	 * @param  integer
	 * @param  string
	 * @return DynamicResponseModel
	 */
	function searchTagTriggers($count = 100, $offset = 0, $match_name = NULL) {
		$query = [];

		$query["count"] = $count;
		$query["offset"] = $offset;
		$query["match_name"] = $match_name;

		return new DynamicResponseModel($this->processRestRequest('GET', '/triggers/tags', $query));
	}

	/**
	 * Edit an existing Tag Trigger
	 * @param  integer
	 * @param  String
	 * @param  boolean
	 * @return DynamicResponseModel
	 */
	function editTagTrigger($id, $matchName, $trackOpens = true) {
		$body = [];
		$body["MatchName"] = $matchName;
		$body["TrackOpens"] = $trackOpens;

		return new DynamicResponseModel($this->processRestRequest('PUT', "/triggers/tags/$id", $body));
	}

	/**
	 * Retrieve information related to the associated Tag Trigger
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function getTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/triggers/tags/$id"));
	}

	/**
	 * Create an Inbound Rule to block messages from a single email address, or an entire domain.
	 * @param  string
	 * @return DynamicResponseModel
	 */
	function createInboundRuleTrigger($rule) {
		$body = [];
		$body["Rule"] = $rule;

		return new DynamicResponseModel($this->processRestRequest('POST', '/triggers/inboundrules', $body));
	}

	/**
	 * Get a list of all existing Inbound Rule Triggers.
	 * @param  integer
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function listInboundRuleTriggers($count = 100, $offset = 0) {
		$query = [];

		$query["count"] = $count;
		$query["offset"] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/triggers/inboundrules', $query));
	}

	/**
	 * Delete an Inbound Rule Trigger.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function deleteInboundRuleTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/inboundrules/$id"));
	}
}

?>