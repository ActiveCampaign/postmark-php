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
	 *
	 * :param string $server_token: The token associated with "Server" you'd like to use to send/receive email from.
	 */
	function __construct($server_token) {
		parent::__construct($server_token, 'X-Postmark-Server-Token');
	}

	/**
	 * Send an email.
	 *
	 * :param  string $from: The sender of the email. (Your account must have an associated Sender Signature for the address used.)
	 * :param  string $to: The recipient of the email.
	 * :param  string $subject: The subject of the email.
	 * :param  string $htmlBody: The HTML content of the message, optional if Text Body is specified.
	 * :param  string $textBody: The text content of the message, optional if HTML Body is specified.
	 * :param  string $tag: A tag associated with this message, useful for classifying sent messages.
	 * :param  boolean $trackOpens: True if you want Postmark to track opens of HTML emails.
	 * :param  string $replyTo: Reply to email address.
	 * :param  string $cc: Carbon Copy recipients, comma-separated
	 * :param  string $bcc: Blind Carbon Copy recipients, comma-separated.
	 * :param  array $headers: Headers to be included with the sent email message.
	 * :param  array $attachments: An array of PostmarkAttachment objects.
	 * :rtype: DyanamicResponseModel
	 */
	function sendEmail($from, $to, $subject, $htmlBody = NULL, $textBody = NULL,
		$tag = NULL, $trackOpens = true, $replyTo = NULL, $cc = NULL, $bcc = NULL,
		$headers = NULL, $attachments = NULL) {

		$body = array();
		$body['From'] = $from;
		$body['To'] = $to;
		$body['Cc'] = $cc;
		$body['Bcc'] = $bcc;
		$body['Subject'] = $subject;
		$body['HtmlBody'] = $htmlBody;
		$body['TextBody'] = $textBody;
		$body['ReplyTo'] = $replyTo;
		$body['Headers'] = $headers;
		$body['TrackOpens'] = $trackOpens;
		$body['Attachments'] = $attachments;

		return new DynamicResponseModel($this->processRestRequest('POST', '/email', $body));
	}

	/**
	 * Send multiple emails as a batch
	 *
	 * :param  array $emailBatch: An array of emails to be sent in one batch. Each email can be an associative array of values, but note that the 'Attachments' key must be an array of 'PostmarkAttachment' objects if you intend to send attachments with an email.
	 * :rtype: DyanamicResponseModel
	 */
	function sendEmailBatch($emailBatch = array()) {
		return new DynamicResponseModel($this->processRestRequest('POST', '/email/batch', $emailBatch));
	}

	/**
	 * Get an overview of the delivery statistics for all email that has been sent through this Server.
	 *
	 * :rtype: DyanamicResponseModel
	 */
	function getDeliveryStatistics() {
		return new DynamicResponseModel($this->processRestRequest('GET', '/deliverystats'));
	}

	/**
	 * Get a batch of bounces to be processed.
	 *
	 * :param  integer $count:
	 * :param  integer $offset:
	 * :param  string $type:
	 * :param  bool $inactive:
	 * :param  string $emailFilter:
	 * :param  string $tag:
	 * :param  string $messageID:
	 * :return DynamicResponseModel
	 */
	function getBounces($count = 100, $offset = 0, $type = NULL,
		$inactive = NULL, $emailFilter = NULL, $tag = NULL, $messageID = NULL) {

		$query = array();
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
	 *
	 * :param  integer $id:
	 * :rtype: DyanamicResponseModel
	 */
	function getBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id"));
	}

	/**
	 * Get a "dump" for a specific bounce.
	 *
	 * :param  integer $id:
	 * :rtype: string
	 */
	function getBounceDump($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id/dump"));
	}

	/**
	 * Cause the email address associated with a Bounce to be reactivated.
	 *
	 * :param  integer $id:
	 * :rtype: DyanamicResponseModel
	 */
	function activateBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/bounces/$id/activate"));
	}

	/**
	 * Get the list of tags associated with messages that have bounced.
	 *
	 * :rtype: array
	 */
	function getBounceTags() {
		return $this->processRestRequest('GET', '/bounces/tags');
	}

	/**
	 * Get the settings for the server associated with this PostmarkClient instance
	 * (defined by the $server_token you passed when instantiating this client)
	 *
	 * :rtype: DyanamicResponseModel
	 */
	function getServer() {
		return new DynamicResponseModel($this->processRestRequest('GET', '/server'));
	}

	/**
	 * Modify the associated Server. Any parameters passed with NULL will be
	 * ignored (their existing values will not be modified).
	 *
	 * :param  string $name:
	 * :param  string $color:
	 * :param  bool $rawEmailEnabled:
	 * :param  bool $smtpApiActivated:
	 * :param  string $inboundHookUrl:
	 * :param  string $bounceHookUrl:
	 * :param  string $openHookUrl:
	 * :param  bool $postFirstOpenOnly:
	 * :param  bool $trackOpens:
	 * :param  string $inboundDomain:
	 * :param  integer $inboundSpamThreshold:
	 * :rtype: DyanamicResponseModel
	 */
	function editServer($name = NULL, $color = NULL, $rawEmailEnabled = NULL,
		$smtpApiActivated = NULL, $inboundHookUrl = NULL, $bounceHookUrl = NULL,
		$openHookUrl = NULL, $postFirstOpenOnly = NULL, $trackOpens = NULL,
		$inboundDomain = NULL, $inboundSpamThreshold = NULL) {

		$body = array();
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
	 *
	 * :param  integer $count:
	 * :param  integer $offset:
	 * :param  string $recipient:
	 * :param  string $fromEmail:
	 * :param  string $tag:
	 * :param  string $subject:
	 * :rtype: DyanamicResponseModel
	 */
	function getOutboundMessages($count = 100, $offset = 0, $recipient = NULL,
		$fromEmail = NULL, $tag = NULL, $subject = NULL) {

		$query = array();
		$query["recipient"] = $recipient;
		$query["fromemail"] = $fromEmail;
		$query["tag"] = $tag;
		$query["subject"] = $subject;
		$query["count"] = $count;
		$query["offset"] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound', $query));
	}

	/**
	 * Get information related to a specific sent message.
	 *
	 * :param integer $id:
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/details"));
	}

	/**
	 * Get the raw content for a message that was sent.
	 *
	 * :param  integer $id:
	 * :rtype: string
	 */
	function getOutboundMessageDump($id) {
		$response = new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/dump"));
		return $response->body;
	}

	/**
	 * Get messages sent to the inbound email address associated with this Server.
	 *
	 * :param  integer $count:
	 * :param  integer $offset:
	 * :param  string $recipient:
	 * :param  string $fromEmail:
	 * :param  string $tag:
	 * :param  string $subject:
	 * :param  string $mailboxHash:
	 * :param  string $status:
	 * :rtype: DynamicResponseModel
	 */
	function getInboundMessages($count = 100, $offset = 0, $recipient = NULL, $fromEmail = NULL,
		$tag = NULL, $subject = NULL, $mailboxHash = NULL, $status = NULL) {

		$query = array();
		$query['recipient'] = $recipient;
		$query['fromemail'] = $fromEmail;
		$query['tag'] = $tag;
		$query['subject'] = $subject;
		$query['mailboxhash'] = $mailboxHash;
		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/messages/inbound', $query));
	}

	/**
	 * Get details for a specific inbound message.
	 *
	 * :param integer $id:
	 * :rtype: DynamicResponseModel
	 */
	function getInboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/inbound/$id/details"));
	}

	/**
	 * Allow an inbound message to be processed, even though the filtering rules would normally
	 * prevent it from being processed.
	 *
	 * :param integer $id:
	 * :rtype: DynamicResponseModel
	 */
	function bypassInboundMessageRules($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/messages/inbound/$id/bypass"));
	}

	/**
	 * Get statistics for tracked messages, optionally filtering by various open event properties.
	 *
	 * :param  integer $count:
	 * :param  integer $offset:
	 * :param  string $recipient:
	 * :param  string $tag:
	 * :param  string $clientName:
	 * :param  string $clientCompany:
	 * :param  string $clientFamily:
	 * :param  string $osName:
	 * :param  string $osFamily:
	 * :param  string $osCompany:
	 * :param  string $platform:
	 * :param  string $country:
	 * :param  string $region:
	 * :param  string $city:
	 * :rtype: DynamicResponseModel
	 */
	function getOpenStatistics($count = 100, $offset = 0, $recipient = NULL,
		$tag = NULL, $clientName = NULL, $clientCompany = NULL, $clientFamily = NULL,
		$osName = NULL, $osFamily = NULL, $osCompany = NULL, $platform = NULL,
		$country = NULL, $region = NULL, $city = NULL) {

		$query = array();
		$query['count'] = $count;
		$query['offset'] = $offset;
		$query['recipient'] = $recipient;
		$query['tag'] = $tag;
		$query['client_name'] = $clientName;
		$query['client_company'] = $clientCompany;
		$query['client_family'] = $clientFamily;
		$query['os_name'] = $osName;
		$query['os_family'] = $osFamily;
		$query['os_company'] = $osCompany;
		$query['platform'] = $platform;
		$query['country'] = $country;
		$query['region'] = $region;
		$query['city'] = $city;

		return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound/opens', $query));
	}

	/**
	 * Get information about individual opens for a sent message.
	 *
	 * :param  integer $id:
	 * :param  integer $count:
	 * :param  integer $offset:
	 * :rtype: DynamicResponseModel
	 */
	function getOpenStatisticsForMessage($id, $count = 100, $offset = 0) {
		$query = array();

		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/opens/$id", $query));
	}

	/**
	 * Get an overview of the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundOverviewStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound', $query));
	}

	/**
	 * Get send statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundSendStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/sends', $query));
	}

	/**
	 * Get bounce statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundBounceStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/bounces', $query));
	}

	/**
	 * Get SPAM complaint statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundSpamComplaintStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/spam', $query));
	}

	/**
	 * Get bounce statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundTrackedStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/tracked', $query));
	}

	/**
	 * Get open statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundOpenStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens', $query));
	}

	/**
	 * Get platform statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundPlatformStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/platforms', $query));
	}

	/**
	 * Get email client statistics for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundEmailClientStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/emailclients', $query));
	}

	/**
	 * Get reading times for the messages sent using this Server,
	 * optionally filtering on message tag, and a to and from date.
	 *
	 * :param  string $tag:
	 * :param  string $fromdate: must be of the format 'YYYY-MM-DD'
	 * :param  string $todate: must be of the format 'YYYY-MM-DD'
	 * :rtype: DynamicResponseModel
	 */
	function getOutboundReadTimeStatistics($tag = NULL, $fromdate = NULL, $todate = NULL) {
		$query = array();

		$query['tag'] = $tag;
		$query['fromdate'] = $fromdate;
		$query['todate'] = $todate;

		return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/readtimes', $query));
	}

	/**
	 * Create a Tag Trigger.
	 *
	 * :param  string $matchName:
	 * :param  boolean $trackOpens:
	 * :rtype: DynamicResponseModel
	 */
	function createTagTrigger($matchName, $trackOpens = true) {
		$body = array();
		$body["MatchName"] = $matchName;
		$body["TrackOpens"] = $trackOpens;

		return new DynamicResponseModel($this->processRestRequest('POST', '/triggers/tags', $body));
	}

	/**
	 * Delete a Tag Trigger with the given ID.
	 *
	 * :param integer $id:
	 * :rtype: DynamicResponseModel
	 */
	function deleteTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/tags/$id"));
	}

	/**
	 * Locate Tag Triggers matching the filter criteria.
	 *
	 * :param  integer $count:
	 * :param  integer $offset:
	 * :param  string $matchName:
	 * :rtype: DynamicResponseModel
	 */
	function searchTagTriggers($count = 100, $offset = 0, $matchName = NULL) {
		$query = array();

		$query["count"] = $count;
		$query["offset"] = $offset;
		$query["match_name"] = $matchName;

		return new DynamicResponseModel($this->processRestRequest('GET', '/triggers/tags', $query));
	}

	/**
	 * Edit an existing Tag Trigger
	 *
	 * :param  integer $id:
	 * :param  string $matchName:
	 * :param  boolean $trackOpens:
	 * :rtype: DynamicResponseModel
	 */
	function editTagTrigger($id, $matchName, $trackOpens = true) {
		$body = array();
		$body["MatchName"] = $matchName;
		$body["TrackOpens"] = $trackOpens;

		return new DynamicResponseModel($this->processRestRequest('PUT', "/triggers/tags/$id", $body));
	}

	/**
	 * Retrieve information related to the associated Tag Trigger
	 *
	 * :param integer $id:
	 * :rtype: DynamicResponseModel
	 */
	function getTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/triggers/tags/$id"));
	}

	/**
	 * Create an Inbound Rule to block messages from a single email address, or an entire domain.
	 *
	 * :param  string $rule:
	 * :rtype: DynamicResponseModel
	 */
	function createInboundRuleTrigger($rule) {
		$body = array();
		$body["Rule"] = $rule;

		return new DynamicResponseModel($this->processRestRequest('POST', '/triggers/inboundrules', $body));
	}

	/**
	 * Get a list of all existing Inbound Rule Triggers.
	 *
	 * :param integer $count:
	 * :param integer $offset:
	 * :rtype: DynamicResponseModel
	 */
	function listInboundRuleTriggers($count = 100, $offset = 0) {
		$query = array();

		$query["count"] = $count;
		$query["offset"] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/triggers/inboundrules', $query));
	}

	/**
	 * Delete an Inbound Rule Trigger.
	 *
	 * :param integer $id:
	 * :rtype: DynamicResponseModel
	 */
	function deleteInboundRuleTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/inboundrules/$id"));
	}
}

?>