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
	 * @param string $server_token  The token associated with "Server" you'd like to use to send/receive email from.
	 * @param integer $timeout The timeout, in seconds to wait for an API call to complete before throwing an Exception.
	 */
	function __construct($serverToken, $timeout = 30) {
		parent::__construct($serverToken, 'X-Postmark-Server-Token', $timeout);
	}

	/**
	 * Send an email.
	 *
	 * @param  string $from: The sender of the email. (Your account must have an associated Sender Signature for the address used.)
	 * @param  string $to  The recipient of the email.
	 * @param  string $subject  The subject of the email.
	 * @param  string $htmlBody  The HTML content of the message, optional if Text Body is specified.
	 * @param  string $textBody  The text content of the message, optional if HTML Body is specified.
	 * @param  string $tag  A tag associated with this message, useful for classifying sent messages.
	 * @param  boolean $trackOpens  True if you want Postmark to track opens of HTML emails.
	 * @param  string $replyTo  Reply to email address.
	 * @param  string $cc  Carbon Copy recipients, comma-separated
	 * @param  string $bcc  Blind Carbon Copy recipients, comma-separated.
	 * @param  array $headers  Headers to be included with the sent email message.
	 * @param  array $attachments  An array of PostmarkAttachment objects.
	 * @return DyanamicResponseModel
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
		$body['Tag'] = $tag;
		$body['ReplyTo'] = $replyTo;
		$body['Headers'] = $this->fixHeaders($headers);
		$body['TrackOpens'] = $trackOpens;
		$body['Attachments'] = $attachments;

		return new DynamicResponseModel($this->processRestRequest('POST', '/email', $body));
	}

	/**
	 * The Postmark API wants an Array of Key-Value pairs, not a dictionary object,
	 * therefore, we need to wrap the elements in an array.
	 */
	private function fixHeaders($headers) {
		$retval = NULL;
		if ($headers != NULL) {
			$retval = [];
			$index = 0;
			foreach ($headers as $key => $value) {
				$retval[$index] = ['Name' => $key, 'Value' => $value];
				$index++;
			}
		}
		return $retval;
	}

	/**
	 * Send multiple emails as a batch
	 *
	 * Each email is an associative array of values, but note that the 'Attachments'
	 * key must be an array of 'PostmarkAttachment' objects if you intend to send
	 * attachments with an email.
	 *
	 * @param array $emailBatch  An array of emails to be sent in one batch.
	 *
	 * @return DyanamicResponseModel
	 */
	function sendEmailBatch($emailBatch = array()) {

		$final = [];

		foreach ($emailBatch as $key => $email) {
			foreach ($email as $emailIdx => $emailValue) {
				if (strtolower($emailIdx) == 'headers') {
					$email[$emailIdx] = $this->fixHeaders($emailValue);
				}
			}
			array_push($final, $email);
		}

		return new DynamicResponseModel($this->processRestRequest('POST', '/email/batch', $final));
	}

	/**
	 * Get an overview of the delivery statistics for all email that has been sent through this Server.
	 *
	 * @return DyanamicResponseModel
	 */
	function getDeliveryStatistics() {
		return new DynamicResponseModel($this->processRestRequest('GET', '/deliverystats'));
	}

	/**
	 * Get a batch of bounces to be processed.
	 *
	 * @param  integer $count Number of bounces to retrieve
	 * @param  integer $offset How many bounces to skip (when paging through bounces.)
	 * @param  string $type The bounce type. (see http://developer.postmarkapp.com/developer-api-bounce.html#bounce-types)
	 * @param  bool $inactive Specifies if the bounce caused Postmark to deactivate this email.
	 * @param  string $emailFilter Filter by email address
	 * @param  string $tag Filter by tag
	 * @param  string $messageID Filter by MessageID
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
	 * @param  integer $id The ID of the bounce to get.
	 * @return DyanamicResponseModel
	 */
	function getBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id"));
	}

	/**
	 * Get a "dump" for a specific bounce.
	 *
	 * @param  integer $id The ID of the bounce for which we want a dump.
	 * @return string
	 */
	function getBounceDump($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id/dump"));
	}

	/**
	 * Cause the email address associated with a Bounce to be reactivated.
	 *
	 * @param  integer $id The bounce which has a deactivated email address.
	 * @return DyanamicResponseModel
	 */
	function activateBounce($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/bounces/$id/activate"));
	}

	/**
	 * Get the list of tags associated with messages that have bounced.
	 *
	 * This produces an array of strings. This one of the only client API calls that does not produce a DynamicResponseModel.
	 *
	 * @return array
	 */
	function getBounceTags() {
		return $this->processRestRequest('GET', '/bounces/tags');
	}

	/**
	 * Get the settings for the server associated with this PostmarkClient instance
	 * (defined by the $server_token you passed when instantiating this client)
	 *
	 * @return DyanamicResponseModel
	 */
	function getServer() {
		return new DynamicResponseModel($this->processRestRequest('GET', '/server'));
	}

	/**
	 * Modify the associated Server. Any parameters passed with NULL will be
	 * ignored (their existing values will not be modified).
	 *
	 * @param  string $name Set the name of the server.
	 * @param  string $color Set the color for the server in the Postmark WebUI (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
	 * @param  bool $rawEmailEnabled Enable raw email to be sent with inbound.
	 * @param  bool $smtpApiActivated Specifies whether or not SMTP is enabled on this server.
	 * @param  string $inboundHookUrl URL to POST to everytime an inbound event occurs.
	 * @param  string $bounceHookUrl URL to POST to everytime a bounce event occurs.
	 * @param  string $openHookUrl URL to POST to everytime an open event occurs.
	 * @param  bool $postFirstOpenOnly If set to true, only the first open by a particular recipient will initiate the open webhook. Any subsequent opens of the same email by the same recipient will not initiate the webhook.
	 * @param  bool $trackOpens Indicates if all emails being sent through this server have open tracking enabled.
	 * @param  string $inboundDomain Inbound domain for MX setup.
	 * @param  integer $inboundSpamThreshold The maximum spam score for an inbound message before it's blocked (range from 0-30).
	 * @return DyanamicResponseModel
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
	 * @param  integer $count How many messages to retrieve at once (defaults to 100)
	 * @param  integer $offset How many messages to skip when 'paging' through the massages (defaults to 0)
	 * @param  string $recipient Filter by recipient.
	 * @param  string $fromEmail Filter by sender email address.
	 * @param  string $tag Filter by tag.
	 * @param  string $subject Filter by subject.
	 * @return DyanamicResponseModel
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
	 * @param integer $id The ID of the Message for which we want details.
	 * @return DynamicResponseModel
	 */
	function getOutboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/details"));
	}

	/**
	 * Get the raw content for a message that was sent.
	 * This response
	 * @param  integer $id The ID of the message for which we want a dump.
	 * @return DynamicResponseModel
	 */
	function getOutboundMessageDump($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/dump"));
	}

	/**
	 * Get messages sent to the inbound email address associated with this Server.
	 *
	 * @param  integer $count The number of inbounce messages to retrieve in the request (defaults to 100)
	 * @param  integer $offset The number of messages to 'skip' when 'paging' through messages (defaults to 0)
	 * @param  string $recipient Filter by the message recipient
	 * @param  string $fromEmail Filter by the message sender
	 * @param  string $tag Filter by the message tag
	 * @param  string $subject Filter by the message subject
	 * @param  string $mailboxHash Filter by the mailboxHash
	 * @param  string $status Filter by status ('blocked' or 'processed')
	 * @return DynamicResponseModel
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
		$query['status'] = $status;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/messages/inbound', $query));
	}

	/**
	 * Get details for a specific inbound message.
	 *
	 * @param integer $id The ID of the message for which we went to get details.
	 * @return DynamicResponseModel
	 */
	function getInboundMessageDetails($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/messages/inbound/$id/details"));
	}

	/**
	 * Allow an inbound message to be processed, even though the filtering rules would normally
	 * prevent it from being processed.
	 *
	 * @param integer $id The ID for a message that we wish to unblock.
	 * @return DynamicResponseModel
	 */
	function bypassInboundMessageRules($id) {
		return new DynamicResponseModel($this->processRestRequest('PUT', "/messages/inbound/$id/bypass"));
	}

	/**
	 * Get statistics for tracked messages, optionally filtering by various open event properties.
	 *
	 * @param  integer $count The number of open statistics to retrieve in this request.
	 * @param  integer $offset The number of statistics to 'skip' when paging through statistics.
	 * @param  string $recipient Filter by recipient.
	 * @param  string $tag Filter by tag.
	 * @param  string $clientName Filter by Email Client name.
	 * @param  string $clientCompany Filter by Email Client Company's name.
	 * @param  string $clientFamily Filter by Email Client's Family name.
	 * @param  string $osName Filter by Email Client's Operating System Name.
	 * @param  string $osFamily Filter by Email Client's Operating System's Family.
	 * @param  string $osCompany Filter by Email Client's Operating System's Company.
	 * @param  string $platform Filter by Email Client's Platform Name.
	 * @param  string $country Filter by Country.
	 * @param  string $region Filter by Region.
	 * @param  string $city Filter by City.
	 * @return DynamicResponseModel
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
	 * @param  integer $id The ID for the message that we want statistics for.
	 * @param  integer $count How many statistics should we retrieve?
	 * @param  integer $offset How many should we 'skip' when 'paging' through statistics.
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $tag Filter by tag.
	 * @param  string $fromdate  must be of the format 'YYYY-MM-DD'
	 * @param  string $todate  must be of the format 'YYYY-MM-DD'
	 * @return DynamicResponseModel
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
	 * @param  string $matchName Name of the tag that will activate this trigger.
	 * @param  boolean $trackOpens Indicates if this trigger activates open tracking.
	 * @return DynamicResponseModel
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
	 * @param integer $id The ID of the Tag Trigger we wish to delete.
	 * @return DynamicResponseModel
	 */
	function deleteTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/tags/$id"));
	}

	/**
	 * Locate Tag Triggers matching the filter criteria.
	 *
	 * @param  integer $count The number of triggers to return with this request.
	 * @param  integer $offset The number of triggers to 'skip' when 'paging' through tag triggers.
	 * @param  string $matchName
	 * @return DynamicResponseModel
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
	 * @param  integer $id The ID of the Tag Trigger we wish to modify.
	 * @param  string $matchName Name of the tag that will activate this trigger.
	 * @param  boolean $trackOpens Indicates if this trigger activates open tracking.
	 * @return DynamicResponseModel
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
	 * @param integer $id The ID of the Tag Trigger we wish to retrieve.
	 * @return DynamicResponseModel
	 */
	function getTagTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/triggers/tags/$id"));
	}

	/**
	 * Create an Inbound Rule to block messages from a single email address, or an entire domain.
	 *
	 * @param  string $rule The email address (or domain) that will be blocked.
	 * @return DynamicResponseModel
	 */
	function createInboundRuleTrigger($rule) {
		$body = array();
		$body["Rule"] = $rule;

		return new DynamicResponseModel($this->processRestRequest('POST', '/triggers/inboundrules', $body));
	}

	/**
	 * Get a list of all existing Inbound Rule Triggers.
	 *
	 * @param integer $count The number of rule triggers to return with this request.
	 * @param integer $offset The number of triggers to 'skip' when 'paging' through rule triggers.
	 * @return DynamicResponseModel
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
	 * @param integer $id The ID of the rule trigger we wish to delete.
	 * @return DynamicResponseModel
	 */
	function deleteInboundRuleTrigger($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/triggers/inboundrules/$id"));
	}
}

?>
