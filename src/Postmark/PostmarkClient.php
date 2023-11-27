<?php

namespace Postmark;

use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Templates\SendEmailWithTemplateResponse;
use Postmark\Models\MessageStream\PostmarkMessageStream;
use Postmark\Models\MessageStream\PostmarkMessageStreamArchivalConfirmation;
use Postmark\Models\MessageStream\PostmarkMessageStreamList;
use Postmark\Models\PostmarkBounce;
use Postmark\Models\PostmarkBounceActivation;
use Postmark\Models\PostmarkBounceDump;
use Postmark\Models\PostmarkBounceList;
use Postmark\Models\PostmarkClickList;
use Postmark\Models\PostmarkDeliveryStats;
use Postmark\Models\PostmarkInboundMessage;
use Postmark\Models\PostmarkInboundMessageList;
use Postmark\Models\PostmarkInboundRuleTrigger;
use Postmark\Models\PostmarkInboundRuleTriggerList;
use Postmark\Models\PostmarkOpenList;
use Postmark\Models\PostmarkOutboundMessageList;
use Postmark\Models\PostmarkResponse;
use Postmark\Models\PostmarkServer;
use Postmark\Models\PostmarkTemplate;
use Postmark\Models\PostmarkTemplateList;
use Postmark\Models\Suppressions\PostmarkSuppressionResultList;
use Postmark\Models\Webhooks\WebhookConfiguration;
use Postmark\Models\Webhooks\WebhookConfigurationListingResponse;

/**
 * PostmarkClient provides the main functionality used to send and analyze email on a "per-Server"
 * basis. If you'd like to manage "Account-wide" configuration, see the PostmarkAdminClient.
 */
class PostmarkClient extends PostmarkClientBase
{
    /**
     * Create a new PostmarkClient.
     *
     * @param string $serverToken the token associated with "Server" you'd like to use to send/receive email from
     * @param int    $timeout     the timeout, in seconds to wait for an API call to complete before throwing an Exception
     */
    public function __construct($serverToken, $timeout = 60)
    {
        parent::__construct($serverToken, 'X-Postmark-Server-Token', $timeout);
    }

    /**
     * Send an email.
     *
     * @param string      $from          The sender of the email. (Your account must have an associated Sender Signature for the address used.)
     * @param string      $to            the recipient of the email
     * @param string      $subject       the subject of the email
     * @param null|string $htmlBody      the HTML content of the message, optional if Text Body is specified
     * @param null|string $textBody      the text content of the message, optional if HTML Body is specified
     * @param null|string $tag           a tag associated with this message, useful for classifying sent messages
     * @param null|bool   $trackOpens    true if you want Postmark to track opens of HTML emails
     * @param null|string $replyTo       reply to email address
     * @param null|string $cc            Carbon Copy recipients, comma-separated
     * @param null|string $bcc           blind Carbon Copy recipients, comma-separated
     * @param null|array  $headers       headers to be included with the sent email message
     * @param null|array  $attachments   an array of PostmarkAttachment objects
     * @param null|string $trackLinks    can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable link tracking
     * @param null|array  $metadata      Add metadata to the message. The metadata is an associative array, and values will be evaluated as strings by Postmark.
     * @param null|string $messageStream The message stream used to send this message. If not provided, the default transactional stream "outbound" will be used.
     *
     * @throws Models\PostmarkException
     */
    public function sendEmail(
        string $from,
        string $to,
        string $subject,
        string $htmlBody = null,
        string $textBody = null,
        string $tag = null,
        bool $trackOpens = null,
        string $replyTo = null,
        string $cc = null,
        string $bcc = null,
        array $headers = null,
        array $attachments = null,
        string $trackLinks = null,
        array $metadata = null,
        string $messageStream = null
    ): DynamicResponseModel {
        $body = [];
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
        $body['Metadata'] = $metadata;
        $body['MessageStream'] = $messageStream;

        // Since this parameter can override a per-server setting
        // we have to check whether it was actually set.
        // And only include it in the API call if that is the case.
        if (null !== $trackLinks) {
            $body['TrackLinks'] = $trackLinks;
        }

        return new DynamicResponseModel((array) $this->processRestRequest('POST', '/email', $body));
    }

    /**
     * Send multiple emails as a batch.
     *
     * Each email is an associative array of values, but note that the 'Attachments'
     * key must be an array of 'PostmarkAttachment' objects if you intend to send
     * attachments with an email.
     *
     * @param array $emailBatch an array of emails to be sent in one batch
     *
     * @return DynamicResponseModel
     */
    public function sendEmailBatch($emailBatch = [])
    {
        $final = [];

        foreach ($emailBatch as $email) {
            foreach ($email as $emailIdx => $emailValue) {
                if ('headers' == strtolower($emailIdx)) {
                    $email[$emailIdx] = $this->fixHeaders($emailValue);
                }
            }
            array_push($final, $email);
        }

        return new DynamicResponseModel((array) $this->processRestRequest('POST', '/email/batch', $final));
    }

    /**
     * Send multiple emails with a template as a batch
     *
     * Each email is an associative array of values. See sendEmailWithTemplate()
     * for details on required values.
     *
     * @param array $emailBatch An array of emails to be sent in one batch.
     *
     * @return DynamicResponseModel
     * @throws Models\PostmarkException
     */
    function sendEmailBatchWithTemplate($emailBatch = array()) {
        $final = array();

        foreach ($emailBatch as $email) {
            foreach ($email as $emailIdx => $emailValue) {
                if (strtolower($emailIdx) === 'headers') {
                    $email[$emailIdx] = $this->fixHeaders($emailValue);
                }
            }
            $final[] = $email;
        }

        return new DynamicResponseModel((array)$this->processRestRequest('POST', '/email/batchWithTemplates', array('Messages' => $final)));
    }

	/**
	 * Send an email using a template.
	 *
	 * @param string $from The sender of the email. (Your account must have an associated Sender Signature for the address used.)
	 * @param string $to The recipient of the email.
	 * @param integer|string $templateIdOrAlias  The ID or alias of the template to use to generate the content of this message.
	 * @param array $templateModel  The values to combine with the Templated content.
	 * @param boolean $inlineCss  If the template contains an HTMLBody, CSS is automatically inlined, you may opt-out of this by passing 'false' for this parameter.
	 * @param string|null $tag  A tag associated with this message, useful for classifying sent messages.
	 * @param boolean|null $trackOpens  True if you want Postmark to track opens of HTML emails.
	 * @param string|null $replyTo  Reply to email address.
	 * @param string|null $cc  Carbon Copy recipients, comma-separated
	 * @param string|null $bcc  Blind Carbon Copy recipients, comma-separated.
	 * @param array|null $headers  Headers to be included with the sent email message.
	 * @param array|null $attachments  An array of PostmarkAttachment objects.
	 * @param string|null $trackLinks  Can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable link tracking.
	 * @param array|null $metadata  Add metadata to the message. The metadata is an associative array , and values will be evaluated as strings by Postmark.
	 * @param array|null $messageStream  The message stream used to send this message. If not provided, the default transactional stream "outbound" will be used.
	 * @return SendEmailWithTemplateResponse
	 */
	function sendEmailWithTemplate(
        string     $from,
        string     $to,
        int|string $templateIdOrAlias,
        array  $templateModel,
        bool   $inlineCss = true,
        string $tag = NULL,
        bool   $trackOpens = NULL,
        string $replyTo = NULL,
        string $cc = NULL,
        string $bcc = NULL,
        array  $headers = NULL,
        array  $attachments = NULL,
        string $trackLinks = NULL,
        array  $metadata = NULL,
        array  $messageStream = NULL): SendEmailWithTemplateResponse
    {
		$body = array();
		$body['From'] = $from;
		$body['To'] = $to;
		$body['Cc'] = $cc;
		$body['Bcc'] = $bcc;
		$body['Tag'] = $tag;
		$body['ReplyTo'] = $replyTo;
		$body['Headers'] = $this->fixHeaders($headers);
		$body['TrackOpens'] = $trackOpens;
		$body['Attachments'] = $attachments;
		$body['TemplateModel'] = $templateModel;
		$body['InlineCss'] = $inlineCss;
		$body['Metadata'] = $metadata;
		$body['MessageStream'] = $messageStream;

		// Since this parameter can override a per-server setting
		// we have to check whether it was actually set.
		// And only include it in the API call if that is the case.
		if ($trackLinks !== NULL) {
			$body['TrackLinks'] = $trackLinks;
		}

		if ( is_int( $templateIdOrAlias ) ) {
			$body['TemplateId'] = $templateIdOrAlias;

			// Uses the Template Alias if specified instead of Template ID.
		} else {
			$body['TemplateAlias'] = $templateIdOrAlias;
		}

		return new SendEmailWithTemplateResponse((array)$this->processRestRequest('POST', '/email/withTemplate', $body));
	}

    /**
     * Get an overview of the delivery statistics for all email that has been sent through this Server.
     */
    public function getDeliveryStatistics(): PostmarkDeliveryStats
    {
        return new PostmarkDeliveryStats((array) $this->processRestRequest('GET', '/deliverystats'));
    }

    /**
     * Get a batch of bounces to be processed.
     *
     * @param int         $count         Number of bounces to retrieve
     * @param int         $offset        How many bounces to skip (when paging through bounces.)
     * @param null|string $type          The bounce type. (see http://developer.postmarkapp.com/developer-api-bounce.html#bounce-types)
     * @param null|bool   $inactive      specifies if the bounce caused Postmark to deactivate this email
     * @param null|string $emailFilter   Filter by email address
     * @param null|string $tag           Filter by tag
     * @param null|int    $messageID     Filter by MessageID
     * @param null|string $fromdate      filter for bounces after is date
     * @param null|string $todate        filter for bounces before this date
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getBounces(
        int $count = 100,
        int $offset = 0,
        ?string $type = null,
        ?bool $inactive = null,
        ?string $emailFilter = null,
        ?string $tag = null,
        ?int $messageID = null,
        ?string $fromdate = null,
        ?string $todate = null,
        ?string $messagestream = null
    ): PostmarkBounceList {
        $query = [];
        $query['type'] = $type;
        $query['inactive'] = $inactive;
        $query['emailFilter'] = $emailFilter;
        $query['tag'] = $tag;
        $query['messageID'] = $messageID;
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new PostmarkBounceList((array) $this->processRestRequest('GET', '/bounces', $query));
    }

    /**
     * Locate information on a specific email bounce.
     *
     * @param int $id The ID of the bounce to get.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     */
    public function getBounce(int $id): PostmarkBounce
    {
        return new PostmarkBounce((array) $this->processRestRequest('GET', "/bounces/{$id}"));
    }

    /**
     * Get a "dump" for a specific bounce.
     *
     * @param int $id The ID of the bounce for which we want a dump.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     */
    public function getBounceDump(int $id): PostmarkBounceDump
    {
        return new PostmarkBounceDump((array) $this->processRestRequest('GET', "/bounces/{$id}/dump"));
    }

    /**
     * Cause the email address associated with a Bounce to be reactivated.
     *
     * @param int $id The bounce which has a deactivated email address.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     */
    public function activateBounce(int $id): PostmarkBounceActivation
    {
        return new PostmarkBounceActivation((array) $this->processRestRequest('PUT', "/bounces/{$id}/activate"));
    }

    /**
     * Get the settings for the server associated with this PostmarkClient instance
     * (defined by the $server_token you passed when instantiating this client).
     */
    public function getServer(): PostmarkServer
    {
        return new PostmarkServer((array) $this->processRestRequest('GET', '/server'));
    }

    /**
     * Modify the associated Server. Any parameters passed with NULL will be
     * ignored (their existing values will not be modified).
     *
     * @param null|string $name                 set the name of the server
     * @param null|string $color                Set the color for the server in the Postmark WebUI (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param null|bool   $rawEmailEnabled      enable raw email to be sent with inbound
     * @param null|bool   $smtpApiActivated     specifies whether or not SMTP is enabled on this server
     * @param null|string $inboundHookUrl       URL to POST to everytime an inbound event occurs
     * @param null|string $bounceHookUrl        URL to POST to everytime a bounce event occurs
     * @param null|string $openHookUrl          URL to POST to everytime an open event occurs
     * @param null|bool   $postFirstOpenOnly    If set to true, only the first open by a particular recipient will initiate the open webhook. Any subsequent opens of the same email by the same recipient will not initiate the webhook.
     * @param null|bool   $trackOpens           indicates if all emails being sent through this server have open tracking enabled
     * @param null|string $inboundDomain        inbound domain for MX setup
     * @param null|int    $inboundSpamThreshold the maximum spam score for an inbound message before it's blocked (range from 0-30)
     * @param null|string $trackLinks           indicates if all emails being sent through this server have link tracking enabled
     * @param null|string $clickHookUrl         URL to POST to everytime an click event occurs
     * @param null|string $deliveryHookUrl      URL to POST to everytime an click event occurs
     */
    public function editServer(
        string $name = null,
        string $color = null,
        bool $rawEmailEnabled = null,
        bool $smtpApiActivated = null,
        string $inboundHookUrl = null,
        string $bounceHookUrl = null,
        string $openHookUrl = null,
        bool $postFirstOpenOnly = null,
        bool $trackOpens = null,
        string $inboundDomain = null,
        int $inboundSpamThreshold = null,
        string $trackLinks = null,
        string $clickHookUrl = null,
        string $deliveryHookUrl = null
    ): PostmarkServer {
        $body = [];
        $body['Name'] = $name;
        $body['Color'] = $color;
        $body['RawEmailEnabled'] = $rawEmailEnabled;
        $body['SmtpApiActivated'] = $smtpApiActivated;
        $body['InboundHookUrl'] = $inboundHookUrl;
        $body['BounceHookUrl'] = $bounceHookUrl;
        $body['OpenHookUrl'] = $openHookUrl;
        $body['PostFirstOpenOnly'] = $postFirstOpenOnly;
        $body['TrackOpens'] = $trackOpens;
        $body['InboundDomain'] = $inboundDomain;
        $body['InboundSpamThreshold'] = $inboundSpamThreshold;
        $body['trackLinks'] = $trackLinks;
        $body['ClickHookUrl'] = $clickHookUrl;
        $body['DeliveryHookUrl'] = $deliveryHookUrl;

        return new PostmarkServer((array) $this->processRestRequest('PUT', '/server', $body));
    }

    /**
     * Search messages that have been sent using this Server.
     *
     * @param int         $count         How many messages to retrieve at once (defaults to 100)
     * @param int         $offset        How many messages to skip when 'paging' through the massages (defaults to 0)
     * @param null|string $recipient     filter by recipient
     * @param null|string $fromEmail     filter by sender email address
     * @param null|string $tag           filter by tag
     * @param null|string $subject       filter by subject
     * @param null|string $status        The current status for the outbound messages to return defaults to 'sent'
     * @param null|string $fromdate      Filter to messages on or after YYYY-MM-DD
     * @param null|string $todate        Filter to messages on or before YYYY-MM-DD
     * @param null|array  $metadata      an associative array of key-values that must all match values included in the metadata of matching sent messages
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundMessages(
        int $count = 100,
        int $offset = 0,
        ?string $recipient = null,
        ?string $fromEmail = null,
        ?string $tag = null,
        ?string $subject = null,
        ?string $status = null,
        ?string $fromdate = null,
        ?string $todate = null,
        ?array $metadata = null,
        ?string $messagestream = null
    ): PostmarkOutboundMessageList {
        $query = [];
        $query['recipient'] = $recipient;
        $query['fromemail'] = $fromEmail;
        $query['tag'] = $tag;
        $query['subject'] = $subject;
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['status'] = $status;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        if (!empty($metadata)) {
            foreach ($metadata as $key => $value) {
                $query["metadata_{$key}"] = $value;
            }
        }

        return new PostmarkOutboundMessageList((array) $this->processRestRequest('GET', '/messages/outbound', $query));
    }

    /**
     * Get information related to a specific sent message.
     *
     * @param string $id the ID of the Message for which we want details
     */
    public function getOutboundMessageDetails(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel((array) $this->processRestRequest('GET', "/messages/outbound/{$id}/details"));
    }

    /**
     * Get the raw content for a message that was sent.
     * This response.
     *
     * @param string $id the ID of the message for which we want a dump
     */
    public function getOutboundMessageDump(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel((array) $this->processRestRequest('GET', "/messages/outbound/{$id}/dump"));
    }

    /**
     * Get messages sent to the inbound email address associated with this Server.
     *
     * @param int         $count       The number of inbounce messages to retrieve in the request (defaults to 100)
     * @param int         $offset      The number of messages to 'skip' when 'paging' through messages (defaults to 0)
     * @param null|string $recipient   Filter by the message recipient
     * @param null|string $fromEmail   Filter by the message sender
     * @param null|string $tag         Filter by the message tag
     * @param null|string $subject     Filter by the message subject
     * @param null|string $mailboxHash Filter by the mailboxHash
     * @param null|string $status      Filter by status ('blocked' or 'processed')
     * @param null|string $fromdate    Filter to messages on or after YYYY-MM-DD
     * @param null|string $todate      Filter to messages on or before YYYY-MM-DD
     */
    public function getInboundMessages(
        int $count = 100,
        int $offset = 0,
        string $recipient = null,
        string $fromEmail = null,
        string $tag = null,
        string $subject = null,
        string $mailboxHash = null,
        string $status = null,
        string $fromdate = null,
        string $todate = null
    ): PostmarkInboundMessageList {
        $query = [];
        $query['recipient'] = $recipient;
        $query['fromemail'] = $fromEmail;
        $query['tag'] = $tag;
        $query['subject'] = $subject;
        $query['mailboxhash'] = $mailboxHash;
        $query['count'] = $count;
        $query['status'] = $status;
        $query['offset'] = $offset;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;

        return new PostmarkInboundMessageList((array) $this->processRestRequest('GET', '/messages/inbound', $query));
    }

    /**
     * Get details for a specific inbound message.
     *
     * @param string $id the ID of the message for which we went to get details
     */
    public function getInboundMessageDetails(string $id): PostmarkInboundMessage
    {
        return new PostmarkInboundMessage((array) $this->processRestRequest('GET', "/messages/inbound/{$id}/details"));
    }

    /**
     * Allow an inbound message to be processed, even though the filtering rules would normally
     * prevent it from being processed.
     *
     * @param string $id the ID for a message that we wish to unblock
     */
    public function bypassInboundMessageRules(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel((array) $this->processRestRequest('PUT', "/messages/inbound/{$id}/bypass"));
    }

    /**
     * Request that Postmark retry POSTing the specified message to the Server's Inbound Hook.
     *
     * @param string $id the ID for a message that we wish retry the inbound hook for
     *
     * @return DynamicResponseModel
     */
    public function retryInboundMessageHook($id)
    {
        return new DynamicResponseModel((array) $this->processRestRequest('PUT', "/messages/inbound/{$id}/retry"));
    }

    /**
     * Get statistics for tracked messages, optionally filtering by various open event properties.
     *
     * @param int         $count         the number of open statistics to retrieve in this request
     * @param int         $offset        the number of statistics to 'skip' when paging through statistics
     * @param null|string $recipient     filter by recipient
     * @param null|string $tag           filter by tag
     * @param null|string $clientName    filter by Email Client name
     * @param null|string $clientCompany filter by Email Client Company's name
     * @param null|string $clientFamily  filter by Email Client's Family name
     * @param null|string $osName        filter by Email Client's Operating System Name
     * @param null|string $osFamily      filter by Email Client's Operating System's Family
     * @param null|string $osCompany     filter by Email Client's Operating System's Company
     * @param null|string $platform      filter by Email Client's Platform Name
     * @param null|string $country       filter by Country
     * @param null|string $region        filter by Region
     * @param null|string $city          filter by City
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOpenStatistics(
        int $count = 100,
        int $offset = 0,
        string $recipient = null,
        string $tag = null,
        string $clientName = null,
        string $clientCompany = null,
        string $clientFamily = null,
        string $osName = null,
        string $osFamily = null,
        string $osCompany = null,
        string $platform = null,
        string $country = null,
        string $region = null,
        string $city = null,
        string $messagestream = null
    ): PostmarkOpenList {
        $query = [];
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
        $query['messagestream'] = $messagestream;

        return new PostmarkOpenList((array) $this->processRestRequest('GET', '/messages/outbound/opens', $query));
    }

    /**
     * Get statistics for tracked messages, optionally filtering by various click event properties.
     *
     * @param int         $count         the number of click statistics to retrieve in this request
     * @param int         $offset        the number of statistics to 'skip' when paging through statistics
     * @param null|string $recipient     filter by recipient
     * @param null|string $tag           filter by tag
     * @param null|string $clientName    filter by Email Client name
     * @param null|string $clientCompany filter by Email Client Company's name
     * @param null|string $clientFamily  filter by Email Client's Family name
     * @param null|string $osName        filter by Email Client's Operating System Name
     * @param null|string $osFamily      filter by Email Client's Operating System's Family
     * @param null|string $osCompany     filter by Email Client's Operating System's Company
     * @param null|string $platform      filter by Email Client's Platform Name
     * @param null|string $country       filter by Country
     * @param null|string $region        filter by Region
     * @param null|string $city          filter by City
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getClickStatistics(
        int $count = 100,
        int $offset = 0,
        string $recipient = null,
        string $tag = null,
        string $clientName = null,
        string $clientCompany = null,
        string $clientFamily = null,
        string $osName = null,
        string $osFamily = null,
        string $osCompany = null,
        string $platform = null,
        string $country = null,
        string $region = null,
        string $city = null,
        string $messagestream = null
    ): PostmarkClickList {
        $query = [];
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
        $query['messagestream'] = $messagestream;

        return new PostmarkClickList((array) $this->processRestRequest('GET', '/messages/outbound/clicks', $query));
    }

    /**
     * Get information about individual opens for a sent message.
     *
     * @param string $id     the ID for the message that we want statistics for
     * @param int    $count  How many statistics should we retrieve?
     * @param int    $offset how many should we 'skip' when 'paging' through statistics
     */
    public function getOpenStatisticsForMessage(string $id, int $count = 100, int $offset = 0): PostmarkOpenList
    {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;

        return new PostmarkOpenList((array) $this->processRestRequest('GET', "/messages/outbound/opens/{$id}", $query));
    }

    /**
     * Get information about individual clicks for a sent message.
     *
     * @param string $id     the ID for the message that we want statistics for
     * @param int    $count  How many statistics should we retrieve?
     * @param int    $offset how many should we 'skip' when 'paging' through statistics
     *
     * @return DynamicResponseModel
     */
    public function getClickStatisticsForMessage($id, $count = 100, $offset = 0)
    {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', "/messages/outbound/clicks/{$id}", $query));
    }

    /**
     * Get an overview of the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param null|string $tag           filter by tag
     * @param null|string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param null|string $todate        must be of the format 'YYYY-MM-DD'
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundOverviewStatistics(
        string $tag = null,
        string $fromdate = null,
        string $todate = null,
        string $messagestream = null
    ): DynamicResponseModel {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound', $query));
    }

    /**
     * Get send statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param null|string $tag           filter by tag
     * @param null|string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param null|string $todate        must be of the format 'YYYY-MM-DD'
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundSendStatistics(
        string $tag = null,
        string $fromdate = null,
        string $todate = null,
        string $messagestream = null
    ): DynamicResponseModel {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/sends', $query));
    }

    /**
     * Get bounce statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param null|string $tag           filter by tag
     * @param null|string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param null|string $todate        must be of the format 'YYYY-MM-DD'
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundBounceStatistics(
        string $tag = null,
        string $fromdate = null,
        string $todate = null,
        string $messagestream = null
    ): DynamicResponseModel {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/bounces', $query));
    }

    /**
     * Get SPAM complaint statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param null|string $tag           filter by tag
     * @param null|string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param null|string $todate        must be of the format 'YYYY-MM-DD'
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundSpamComplaintStatistics(
        string $tag = null,
        string $fromdate = null,
        string $todate = null,
        string $messagestream = null
    ): DynamicResponseModel {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/spam', $query));
    }

    /**
     * Get bounce statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param null|string $tag           filter by tag
     * @param null|string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param null|string $todate        must be of the format 'YYYY-MM-DD'
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundTrackedStatistics(
        string $tag = null,
        string $fromdate = null,
        string $todate = null,
        string $messagestream = null
    ): DynamicResponseModel {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/tracked', $query));
    }

    /**
     * Get open statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string $tag           filter by tag
     * @param string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string $todate        must be of the format 'YYYY-MM-DD'
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     *
     * @return DynamicResponseModel
     */
    public function getOutboundOpenStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null)
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/opens', $query));
    }

    /**
     * Get platform statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param null|string $tag           filter by tag
     * @param null|string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param null|string $todate        must be of the format 'YYYY-MM-DD'
     * @param null|string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundPlatformStatistics(
        string $tag = null,
        string $fromdate = null,
        string $todate = null,
        string $messagestream = null
    ): DynamicResponseModel {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/opens/platforms', $query));
    }

    /**
     * Get email client statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string $tag           filter by tag
     * @param string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string $todate        must be of the format 'YYYY-MM-DD'
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     *
     * @return DynamicResponseModel
     */
    public function getOutboundEmailClientStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null)
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/opens/emailclients', $query));
    }

    /**
     * Get reading times for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string $tag      filter by tag
     * @param string $fromdate must be of the format 'YYYY-MM-DD'
     * @param string $todate   must be of the format 'YYYY-MM-DD'
     *
     * @return DynamicResponseModel
     */
    public function getOutboundReadTimeStatistics($tag = null, $fromdate = null, $todate = null)
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/opens/readtimes', $query));
    }

    /**
     * Get click statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string $tag           filter by tag
     * @param string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string $todate        must be of the format 'YYYY-MM-DD'
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     *
     * @return DynamicResponseModel
     */
    public function getOutboundClickStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null)
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/clicks', $query));
    }

    /**
     * Get information about which browsers were used to open tracked links for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string $tag           filter by tag
     * @param string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string $todate        must be of the format 'YYYY-MM-DD'
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     *
     * @return DynamicResponseModel
     */
    public function getOutboundClickBrowserFamilyStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null)
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/clicks/browserfamilies', $query));
    }

    /**
     * Get information about which browsers platforms (Desktop, Mobile, etc.) were used to open
     * tracked links for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string $tag           filter by tag
     * @param string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string $todate        must be of the format 'YYYY-MM-DD'
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     *
     * @return DynamicResponseModel
     */
    public function getOutboundClickBrowserPlatformStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null)
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/clicks/platforms', $query));
    }

    /**
     * Get information about part of the message (HTML or Text)
     * tracked links were clicked from in messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string $tag           filter by tag
     * @param string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string $todate        must be of the format 'YYYY-MM-DD'
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     *
     * @return DynamicResponseModel
     */
    public function getOutboundClickLocationStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null)
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel((array) $this->processRestRequest('GET', '/stats/outbound/clicks/location', $query));
    }

    /**
     * Create an Inbound Rule to block messages from a single email address, or an entire domain.
     *
     * @param string $rule the email address (or domain) that will be blocked
     */
    public function createInboundRuleTrigger($rule): PostmarkInboundRuleTrigger
    {
        $body = [];
        $body['Rule'] = $rule;

        return new PostmarkInboundRuleTrigger((array) $this->processRestRequest('POST', '/triggers/inboundrules', $body));
    }

    /**
     * Get a list of all existing Inbound Rule Triggers.
     *
     * @param int $count  the number of rule triggers to return with this request
     * @param int $offset the number of triggers to 'skip' when 'paging' through rule triggers
     */
    public function listInboundRuleTriggers(int $count = 100, int $offset = 0): PostmarkInboundRuleTriggerList
    {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;

        return new PostmarkInboundRuleTriggerList((array) $this->processRestRequest('GET', '/triggers/inboundrules', $query));
    }

    /**
     * Delete an Inbound Rule Trigger.
     *
     * @param int $id the ID of the rule trigger we wish to delete
     */
    public function deleteInboundRuleTrigger(int $id): PostmarkResponse
    {
        return new PostmarkResponse((array) $this->processRestRequest('DELETE', "/triggers/inboundrules/{$id}"));
    }

    /**
     * Delete a template.
     *
     * @param string $id the ID or alias of the template to delete
     */
    public function deleteTemplate(string $id): PostmarkResponse
    {
        return new PostmarkResponse((array) $this->processRestRequest('DELETE', "/templates/{$id}"));
    }

    /**
     * Create a template.
     *
     * @param string      $name           the friendly name for this template
     * @param string      $subject        the template to be used for the 'subject' of emails sent using this template
     * @param string      $htmlBody       the template to be used for the 'htmlBody' of emails sent using this template, optional if 'textBody' is not NULL
     * @param string      $textBody       the template to be used for the 'textBody' of emails sent using this template, optional if 'htmlBody' is not NULL
     * @param null|string $alias          An optional string you can provide to identify this Template. Allowed characters are numbers, ASCII letters, and ‘.’, ‘-’, ‘_’ characters, and the string has to start with a letter.
     * @param string      $templateType   Creates the template based on the template type provided. Possible options: Standard or Layout. Defaults to Standard.
     * @param null|string $layoutTemplate The alias of the Layout template that you want to use as layout for this Standard template. If not provided, a standard template will not use a layout template.
     */
    public function createTemplate(
        string $name,
        ?string $subject,
        ?string $htmlBody,
        ?string $textBody,
        ?string $alias = null,
        string $templateType = 'Standard',
        string $layoutTemplate = null
    ): PostmarkTemplate {
        $template = [];
        $template['name'] = $name;
        $template['subject'] = $subject;
        $template['htmlBody'] = $htmlBody;
        $template['textBody'] = $textBody;
        $template['alias'] = $alias;
        $template['templateType'] = $templateType;
        $template['layoutTemplate'] = $layoutTemplate;

        return new PostmarkTemplate((array) $this->processRestRequest('POST', '/templates', $template));
    }

    /**
     * Edit a template.
     *
     * @param string      $id             the ID or alias of the template you wish to update
     * @param null|string $name           the friendly name for this template
     * @param null|string $subject        the template to be used for the 'subject' of emails sent using this template
     * @param null|string $htmlBody       the template to be used for the 'htmlBody' of emails sent using this template
     * @param null|string $textBody       the template to be used for the 'textBody' of emails sent using this template
     * @param null|string $alias          An optional string you can provide to identify this Template. Allowed characters are numbers, ASCII letters, and ‘.’, ‘-’, ‘_’ characters, and the string has to start with a letter.
     * @param null|string $layoutTemplate The alias of the Layout template that you want to use as layout for this Standard template. If not provided, a standard template will not use a layout template.
     */
    public function editTemplate(
        string $id,
        string $name = null,
        string $subject = null,
        string $htmlBody = null,
        string $textBody = null,
        string $alias = null,
        string $layoutTemplate = null
    ): PostmarkTemplate {
        $template = [];
        $template['name'] = $name;
        $template['subject'] = $subject;
        $template['htmlBody'] = $htmlBody;
        $template['textBody'] = $textBody;
        $template['alias'] = $alias;
        $template['layoutTemplate'] = $layoutTemplate;

        return new PostmarkTemplate((array) $this->processRestRequest('PUT', "/templates/{$id}", $template));
    }

    /**
     * Get the current information for a specific template.
     *
     * @param int $id the Id for the template info you wish to retrieve
     */
    public function getTemplate(int $id): PostmarkTemplate
    {
        return new PostmarkTemplate((array) $this->processRestRequest('GET', "/templates/{$id}"));
    }

    /**
     * Get the current information for a specific template.
     *
     * @param string $alias the alias for the template info you wish to retrieve
     */
    public function getTemplateByAlias(string $alias): PostmarkTemplate
    {
        return new PostmarkTemplate((array) $this->processRestRequest('GET', "/templates/{$alias}"));
    }

    /**
     * Get all templates associated with the Server.
     *
     * @param int         $count          The total number of templates to get at once (default is 100)
     * @param int         $offset         the number of templates to "Skip" before returning results
     * @param string      $templateType   Filters the results based on the template type provided. Possible options: Standard, Layout, All. Defaults to All.
     * @param null|string $layoutTemplate Filters the results based on the layout template alias. Defaults to NULL.
     */
    public function listTemplates(
        int $count = 100,
        int $offset = 0,
        string $templateType = 'All',
        string $layoutTemplate = null
    ): PostmarkTemplateList {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['templateType'] = $templateType;
        $query['layoutTemplate'] = $layoutTemplate;

        return new PostmarkTemplateList((array) $this->processRestRequest('GET', '/templates', $query));
    }

    /**
     * Confirm that your template content can be parsed/rendered, get a test rendering of your template, and a suggested model to use with your templates.
     *
     * @param null|string $subject                    the Subject template you wish to test
     * @param null|string $htmlBody                   The HTML template you wish to test
     * @param null|string $textBody                   the number of templates to "Skip" before returning results
     * @param null|object $testRenderModel            the model to be used when doing test renders of the templates that successfully parse in this request
     * @param bool        $inlineCssForHtmlTestRender If htmlBody is specified, the test render will automatically do CSS Inlining for the HTML content. You may opt-out of this behavior by passing 'false' for this parameter.
     * @param string      $templateType               Validates templates based on template type (layout template or standard template). Possible options: Standard or Layout. Defaults to Standard.
     * @param null|string $layoutTemplate             An optional string to specify which layout template alias to use to validate a standard template. If not provided a standard template will not use a layout template.
     */
    public function validateTemplate(
        string $subject = null,
        string $htmlBody = null,
        string $textBody = null,
        object $testRenderModel = null,
        bool $inlineCssForHtmlTestRender = true,
        string $templateType = 'Standard',
        string $layoutTemplate = null
    ): DynamicResponseModel {
        $query = [];

        $query['subject'] = $subject;
        $query['htmlBody'] = $htmlBody;
        $query['textBody'] = $textBody;
        $query['testRenderModel'] = $testRenderModel;
        $query['inlineCssForHtmlTestRender'] = $inlineCssForHtmlTestRender;
        $query['templateType'] = $templateType;
        $query['layoutTemplate'] = $layoutTemplate;

        return new DynamicResponseModel((array) $this->processRestRequest('POST', '/templates/validate', $query));
    }

    /**
     * Get information about a specific webhook configuration.
     *
     * @param int $id the Id of the webhook configuration you wish to retrieve
     */
    public function getWebhookConfiguration(int $id): WebhookConfiguration
    {
        return new WebhookConfiguration((array) $this->processRestRequest('GET', "/webhooks/{$id}"));
    }

    /**
     * Get all webhook configurations associated with the Server.
     *
     * @param null|string $messageStream Optional message stream to filter results by. If not provided, all configurations for the server will be returned.
     */
    public function getWebhookConfigurations(string $messageStream = null): WebhookConfigurationListingResponse
    {
        $query = [];
        $query['MessageStream'] = $messageStream;

        return new WebhookConfigurationListingResponse((array) $this->processRestRequest('GET', '/webhooks', $query));
    }

    /**
     * Delete a webhook configuration.
     *
     * @param int $id the Id of the webhook configuration you wish to delete
     */
    public function deleteWebhookConfiguration(int $id): PostmarkResponse
    {
        return new PostmarkResponse((array) $this->processRestRequest('DELETE', "/webhooks/{$id}"));
    }

    /**
     * Create a webhook configuration.
     *
     * @param string      $url           the webhook URL
     * @param null|string $messageStream Message stream this configuration should belong to. If not provided, it will belong to the default transactional stream.
     * @param null|object $httpAuth      optional Basic HTTP Authentication
     * @param null|array  $httpHeaders   optional list of custom HTTP headers
     * @param null|object $triggers      optional triggers for this webhook configuration
     */
    public function createWebhookConfiguration(
        string $url,
        string $messageStream = null,
        object $httpAuth = null,
        array $httpHeaders = null,
        object $triggers = null
    ): WebhookConfiguration {
        $body = [];
        $body['Url'] = $url;
        $body['MessageStream'] = $messageStream;
        $body['HttpAuth'] = $httpAuth;
        $body['HttpHeaders'] = $this->fixHeaders($httpHeaders);
        $body['Triggers'] = $triggers;

        return new WebhookConfiguration((array) $this->processRestRequest('POST', '/webhooks', $body));
    }

    /**
     * Edit a webhook configuration.
     * Any parameters passed with NULL will be ignored (their existing values will not be modified).
     *
     * @param int         $id          the Id of the webhook configuration you wish to edit
     * @param null|string $url         optional webhook URL
     * @param null|object $httpAuth    optional Basic HTTP Authentication
     * @param null|array  $httpHeaders optional list of custom HTTP headers
     * @param null|object $triggers    optional triggers for this webhook configuration
     */
    public function editWebhookConfiguration(
        int $id,
        string $url = null,
        object $httpAuth = null,
        array $httpHeaders = null,
        object $triggers = null
    ): WebhookConfiguration {
        $body = [];
        $body['Url'] = $url;
        $body['HttpAuth'] = $httpAuth;
        $body['HttpHeaders'] = $this->fixHeaders($httpHeaders);
        $body['Triggers'] = $triggers;

        return new WebhookConfiguration((array) $this->processRestRequest('PUT', "/webhooks/{$id}", $body));
    }

    /**
     * Create Suppressions for the specified recipients.
     *
     * @param array|string $suppressionChanges array of SuppressionChangeRequest objects that specify what recipients to suppress
     * @param null|string  $messageStream      Message stream where the recipients should be suppressed. If not provided, they will be suppressed on the default transactional stream.
     *
     * Suppressions will be generated with a Customer Origin and will have a ManualSuppression reason.
     */
    public function createSuppressions(array|string $suppressionChanges = [], string $messageStream = null): PostmarkSuppressionResultList
    {
        $body = [];
        $body['Suppressions'] = $suppressionChanges;

        if (null === $messageStream) {
            $messageStream = 'outbound';
        }

        return new PostmarkSuppressionResultList((array) $this->processRestRequest('POST', "/message-streams/{$messageStream}/suppressions", $body));
    }

    /**
     * Reactivate Suppressions for the specified recipients.
     *
     * @param array|string $suppressionChanges array of SuppressionChangeRequest objects that specify what recipients to reactivate
     * @param null|string  $messageStream      Message stream where the recipients should be reactivated. If not provided, they will be reactivated on the default transactional stream.
     *
     * Only 'Customer' origin 'ManualSuppression' suppressions and 'Recipient' origin 'HardBounce' suppressions can be reactivated.
     */
    public function deleteSuppressions(array|string $suppressionChanges = [], string $messageStream = null): PostmarkSuppressionResultList
    {
        $body = [];
        $body['Suppressions'] = $suppressionChanges;

        if (null === $messageStream) {
            $messageStream = 'outbound';
        }

        return new PostmarkSuppressionResultList((array) $this->processRestRequest('POST', "/message-streams/{$messageStream}/suppressions/delete", $body));
    }

    /**
     * List Suppressions that match the provided query parameters.
     *
     * @param null|string $messageStream     Filter Suppressions by MessageStream. If not provided, Suppressions for the default transactional stream will be returned. (optional)
     * @param null|string $suppressionReason Filter Suppressions by reason. E.g.: HardBounce, SpamComplaint, ManualSuppression. (optional)
     * @param null|string $origin            Filter Suppressions by the origin that created them. E.g.: Customer, Recipient, Admin. (optional)
     * @param null|string $fromDate          Filter suppressions from the date specified - inclusive. (optional)
     * @param null|string $toDate            Filter suppressions up to the date specified - inclusive. (optional)
     * @param null|string $emailAddress      Filter by a specific email address. (optional)
     */
    public function getSuppressions(
        string $messageStream = null,
        string $suppressionReason = null,
        string $origin = null,
        string $fromDate = null,
        string $toDate = null,
        string $emailAddress = null
    ): PostmarkSuppressionResultList {
        $query = [];
        $query['SuppressionReason'] = $suppressionReason;
        $query['Origin'] = $origin;
        $query['FromDate'] = $fromDate;
        $query['ToDate'] = $toDate;
        $query['EmailAddress'] = $emailAddress;

        if (null === $messageStream) {
            $messageStream = 'outbound';
        }

        return new PostmarkSuppressionResultList((array) $this->processRestRequest('GET', "/message-streams/{$messageStream}/suppressions/dump", $query));
    }

    /**
     * Create a new message stream on your server.
     *
     * @param string      $id                identifier for your message stream, unique at server level
     * @param string      $messageStreamType Type of the message stream. Possible values: ["Transactional", "Inbound", "Broadcasts"].
     * @param string      $name              friendly name for your message stream
     * @param null|string $description       Friendly description for your message stream. (optional)
     *
     * Currently, you cannot create multiple inbound streams.
     */
    public function createMessageStream(string $id, string $messageStreamType, string $name, string $description = null): PostmarkMessageStream
    {
        $body = [];
        $body['ID'] = $id;
        $body['MessageStreamType'] = $messageStreamType;
        $body['Name'] = $name;
        $body['Description'] = $description;

        return new PostmarkMessageStream((array) $this->processRestRequest('POST', '/message-streams', $body));
    }

    /**
     * Edit the properties of a message stream.
     *
     * @param string      $id          the identifier for the stream you are trying to update
     * @param null|string $name        New friendly name to use. (optional)
     * @param null|string $description New description to use. (optional)
     */
    public function editMessageStream(string $id, string $name = null, string $description = null): PostmarkMessageStream
    {
        $body = [];
        $body['Name'] = $name;
        $body['Description'] = $description;

        return new PostmarkMessageStream((array) $this->processRestRequest('PATCH', "/message-streams/{$id}", $body));
    }

    /**
     * Retrieve details about a message stream.
     *
     * @param string $id identifier of the stream to retrieve details for
     */
    public function getMessageStream(string $id): PostmarkMessageStream
    {
        return new PostmarkMessageStream((array) $this->processRestRequest('GET', "/message-streams/{$id}"));
    }

    /**
     * Retrieve all message streams on the server.
     *
     * @param string $messageStreamType      Filter by stream type. Possible values: ["Transactional", "Inbound", "Broadcasts", "All"]. Defaults to: All.
     * @param string $includeArchivedStreams Include archived streams in the result. Defaults to: false.
     */
    public function listMessageStreams(string $messageStreamType = 'All', string $includeArchivedStreams = 'false'): PostmarkMessageStreamList
    {
        $query = [];
        $query['MessageStreamType'] = $messageStreamType;
        $query['IncludeArchivedStreams'] = $includeArchivedStreams;

        return new PostmarkMessageStreamList((array) $this->processRestRequest('GET', '/message-streams', $query));
    }

    /**
     * Archive a message stream. This will disable sending/receiving messages via that stream.
     * The stream will also stop being shown in the Postmark UI.
     * Once a stream has been archived, it will be deleted (alongside associated data) at the ExpectedPurgeDate in the response.
     *
     * @param string $id the identifier for the stream you are trying to update
     */
    public function archiveMessageStream(string $id): PostmarkMessageStreamArchivalConfirmation
    {
        return new PostmarkMessageStreamArchivalConfirmation((array) $this->processRestRequest('POST', "/message-streams/{$id}/archive"));
    }

    /**
     * Unarchive a message stream. This will resume sending/receiving via that stream.
     * The stream will also re-appear in the Postmark UI.
     * A stream can be unarchived only before the stream ExpectedPurgeDate.
     *
     * @param string $id identifier of the stream to unarchive
     */
    public function unarchiveMessageStream(string $id): PostmarkMessageStream
    {
        return new PostmarkMessageStream((array) $this->processRestRequest('POST', "/message-streams/{$id}/unarchive"));
    }

    /**
     * The Postmark API wants an Array of Key-Value pairs, not a dictionary object,
     * therefore, we need to wrap the elements in an array.
     *
     * @param mixed $headers
     */
    private function fixHeaders($headers)
    {
        $retval = null;
        if (null != $headers) {
            $retval = [];
            $index = 0;
            foreach ($headers as $key => $value) {
                $retval[$index] = ['Name' => $key, 'Value' => $value];
                ++$index;
            }
        }

        return $retval;
    }
}
