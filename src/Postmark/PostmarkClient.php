<?php

declare(strict_types=1);

namespace Postmark;

use Postmark\ClientBehaviour\InboundMessages;
use Postmark\ClientBehaviour\MessageStreams;
use Postmark\ClientBehaviour\PostmarkClientBase;
use Postmark\ClientBehaviour\Suppressions;
use Postmark\ClientBehaviour\Templates;
use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Header;
use Postmark\Models\PostmarkAttachment;

use function is_int;
use function strtolower;

/**
 * @link PostmarkAttachment
 *
 * @psalm-type Attachments = list<PostmarkAttachment>|null
 * @psalm-type HeaderList = array<non-empty-string, scalar|null>|null
 * @psalm-type MetaData = array<non-empty-string, scalar>|null
 */
final class PostmarkClient extends PostmarkClientBase
{
    use InboundMessages;
    use MessageStreams;
    use Suppressions;
    use Templates;

    private const AUTH_HEADER_NAME = 'X-Postmark-Server-Token';

    protected function authorizationHeaderName(): string
    {
        return self::AUTH_HEADER_NAME;
    }

    /**
     * Send an email.
     *
     * @param string      $from          The sender of the email. (Your account must have an associated Sender Signature
     *                                   for the address used.)
     * @param string      $to            The recipient of the email.
     * @param string      $subject       The subject of the email.
     * @param string|null $htmlBody      The HTML content of the message, optional if Text Body is specified.
     * @param string|null $textBody      The text content of the message, optional if HTML Body is specified.
     * @param string|null $tag           A tag associated with this message, useful for classifying sent messages.
     * @param bool|null   $trackOpens    True if you want Postmark to track opens of HTML emails.
     * @param string|null $replyTo       Reply to email address.
     * @param string|null $cc            Carbon Copy recipients, comma-separated
     * @param string|null $bcc           Blind Carbon Copy recipients, comma-separated.
     * @param HeaderList  $headers       Headers to be included with the sent email message.
     * @param Attachments $attachments   An array of PostmarkAttachment objects.
     * @param string|null $trackLinks    Can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable link
     *                                   tracking.
     * @param MetaData    $metadata      Add metadata to the message. The metadata is an associative array, and values
     *                                   will be evaluated as strings by Postmark.
     * @param string|null $messageStream The message stream used to send this message. If not provided, the default
     *                                   transactional stream "outbound" will be used.
     */
    public function sendEmail(
        string $from,
        string $to,
        string $subject,
        ?string $htmlBody = null,
        ?string $textBody = null,
        ?string $tag = null,
        ?bool $trackOpens = null,
        ?string $replyTo = null,
        ?string $cc = null,
        ?string $bcc = null,
        ?array $headers = null,
        ?array $attachments = null,
        ?string $trackLinks = null,
        ?array $metadata = null,
        ?string $messageStream = null
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
        $body['Headers'] = Header::listFromArray($headers);
        $body['TrackOpens'] = $trackOpens;
        $body['Attachments'] = $attachments;
        $body['Metadata'] = $metadata;
        $body['MessageStream'] = $messageStream;

        // Since this parameter can override a per-server setting
        // we have to check whether it was actually set.
        // And only include it in the API call if that is the case.
        if ($trackLinks !== null) {
            $body['TrackLinks'] = $trackLinks;
        }

        return new DynamicResponseModel($this->processRestRequest('POST', '/email', $body));
    }

    /**
     * Send an email using a template.
     *
     * @param string      $from              The sender of the email. (Your account must have an associated Sender
     *                                       Signature for the address used.)
     * @param string      $to                The recipient of the email.
     * @param int|string  $templateIdOrAlias The ID or alias of the template to use to generate the content of this
     *                                       message.
     * @param array       $templateModel     The values to combine with the Templated content.
     * @param bool        $inlineCss         If the template contains an HTMLBody, CSS is automatically inlined, you
     *                                       may opt-out of this by passing 'false' for this parameter.
     * @param string      $tag               A tag associated with this message, useful for classifying sent messages.
     * @param bool        $trackOpens        True if you want Postmark to track opens of HTML emails.
     * @param string      $replyTo           Reply to email address.
     * @param string      $cc                Carbon Copy recipients, comma-separated
     * @param string      $bcc               Blind Carbon Copy recipients, comma-separated.
     * @param HeaderList  $headers           Headers to be included with the sent email message.
     * @param Attachments $attachments       An array of PostmarkAttachment objects.
     * @param string      $trackLinks        Can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable
     *                                       link tracking.
     * @param MetaData    $metadata          Add metadata to the message. The metadata is an associative array , and
     *                                       values will be evaluated as strings by Postmark.
     * @param string      $messageStream     The message stream used to send this message. If not provided, the default
     *                                      transactional stream "outbound" will be used.
     */
    public function sendEmailWithTemplate(
        $from,
        $to,
        $templateIdOrAlias,
        $templateModel,
        $inlineCss = true,
        $tag = null,
        $trackOpens = null,
        $replyTo = null,
        $cc = null,
        $bcc = null,
        $headers = null,
        $attachments = null,
        $trackLinks = null,
        $metadata = null,
        $messageStream = null
    ): DynamicResponseModel {
        $body = [];
        $body['From'] = $from;
        $body['To'] = $to;
        $body['Cc'] = $cc;
        $body['Bcc'] = $bcc;
        $body['Tag'] = $tag;
        $body['ReplyTo'] = $replyTo;
        $body['Headers'] = Header::listFromArray($headers);
        $body['TrackOpens'] = $trackOpens;
        $body['Attachments'] = $attachments;
        $body['TemplateModel'] = $templateModel;
        $body['InlineCss'] = $inlineCss;
        $body['Metadata'] = $metadata;
        $body['MessageStream'] = $messageStream;

        // Since this parameter can override a per-server setting
        // we have to check whether it was actually set.
        // And only include it in the API call if that is the case.
        if ($trackLinks !== null) {
            $body['TrackLinks'] = $trackLinks;
        }

        if (is_int($templateIdOrAlias)) {
            $body['TemplateId'] = $templateIdOrAlias;

            // Uses the Template Alias if specified instead of Template ID.
        } else {
            $body['TemplateAlias'] = $templateIdOrAlias;
        }

        return new DynamicResponseModel($this->processRestRequest('POST', '/email/withTemplate', $body));
    }

    /**
     * The Postmark API wants an Array of Key-Value pairs, not a dictionary object,
     * therefore, we need to wrap the elements in an array.
     */
    private function fixHeaders($headers)
    {
        $retval = null;
        if ($headers != null) {
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
     * @param array $emailBatch An array of emails to be sent in one batch.
     */
    public function sendEmailBatch($emailBatch = []): DynamicResponseModel
    {
        $final = [];

        foreach ($emailBatch as $email) {
            foreach ($email as $emailIdx => $emailValue) {
                if (strtolower($emailIdx) == 'headers') {
                    $email[$emailIdx] = Header::listFromArray($emailValue);
                }
            }

            $final[] = $email;
        }

        return new DynamicResponseModel($this->processRestRequest('POST', '/email/batch', $final));
    }

    /**
     * Send multiple emails with a template as a batch
     *
     * Each email is an associative array of values. See sendEmailWithTemplate()
     * for details on required values.
     *
     * @param array $emailBatch An array of emails to be sent in one batch.
     */
    public function sendEmailBatchWithTemplate($emailBatch = []): DynamicResponseModel
    {
        $final = [];

        foreach ($emailBatch as $email) {
            foreach ($email as $emailIdx => $emailValue) {
                if (strtolower($emailIdx) !== 'headers') {
                    continue;
                }

                $email[$emailIdx] = Header::listFromArray($emailValue);
            }

            $final[] = $email;
        }

        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/email/batchWithTemplates', ['Messages' => $final])
        );
    }

    /**
     * Get an overview of the delivery statistics for all email that has been sent through this Server.
     */
    public function getDeliveryStatistics(): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', '/deliverystats'));
    }

    /**
     * Get a batch of bounces to be processed.
     *
     * @param int    $count         Number of bounces to retrieve
     * @param int    $offset        How many bounces to skip (when paging through bounces.)
     * @param string $type          The bounce type. (see http://developer.postmarkapp.com/developer-api-bounce.html#bounce-types)
     * @param bool   $inactive      Specifies if the bounce caused Postmark to deactivate this email.
     * @param string $emailFilter   Filter by email address
     * @param string $tag           Filter by tag
     * @param string $messageID     Filter by MessageID
     * @param string $fromdate      Filter for bounces after is date.
     * @param string $todate        Filter for bounces before this date.
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getBounces(
        $count = 100,
        $offset = 0,
        $type = null,
        $inactive = null,
        $emailFilter = null,
        $tag = null,
        $messageID = null,
        $fromdate = null,
        $todate = null,
        $messagestream = null
    ): DynamicResponseModel {
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

        return new DynamicResponseModel($this->processRestRequest('GET', '/bounces', $query));
    }

    /**
     * Locate information on a specific email bounce.
     *
     * @param int $id The ID of the bounce to get.
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     */
    public function getBounce($id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id"));
    }

    /**
     * Get a "dump" for a specific bounce.
     *
     * @param int $id The ID of the bounce for which we want a dump.
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     */
    public function getBounceDump($id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', "/bounces/$id/dump"));
    }

    /**
     * Cause the email address associated with a Bounce to be reactivated.
     *
     * @param int $id The bounce which has a deactivated email address.
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     */
    public function activateBounce($id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('PUT', "/bounces/$id/activate"));
    }

    /**
     * Get the settings for the server associated with this PostmarkClient instance
     * (defined by the $server_token you passed when instantiating this client)
     */
    public function getServer(): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', '/server'));
    }

    /**
     * Modify the associated Server. Any parameters passed with NULL will be
     * ignored (their existing values will not be modified).
     *
     * @param string $name                 Set the name of the server.
     * @param string $color                Set the color for the server in the Postmark WebUI (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param bool   $rawEmailEnabled      Enable raw email to be sent with inbound.
     * @param bool   $smtpApiActivated     Specifies whether or not SMTP is enabled on this server.
     * @param string $inboundHookUrl       URL to POST to everytime an inbound event occurs.
     * @param string $bounceHookUrl        URL to POST to everytime a bounce event occurs.
     * @param string $openHookUrl          URL to POST to everytime an open event occurs.
     * @param bool   $postFirstOpenOnly    If set to true, only the first open by a particular recipient will initiate the open webhook. Any subsequent opens of the same email by the same recipient will not initiate the webhook.
     * @param bool   $trackOpens           Indicates if all emails being sent through this server have open tracking enabled.
     * @param string $inboundDomain        Inbound domain for MX setup.
     * @param int    $inboundSpamThreshold The maximum spam score for an inbound message before it's blocked (range from 0-30).
     * @param string $trackLinks           Indicates if all emails being sent through this server have link tracking enabled.
     * @param string $clickHookUrl         URL to POST to everytime an click event occurs.
     * @param string $deliveryHookUrl      URL to POST to everytime an click event occurs.
     */
    public function editServer(
        $name = null,
        $color = null,
        $rawEmailEnabled = null,
        $smtpApiActivated = null,
        $inboundHookUrl = null,
        $bounceHookUrl = null,
        $openHookUrl = null,
        $postFirstOpenOnly = null,
        $trackOpens = null,
        $inboundDomain = null,
        $inboundSpamThreshold = null,
        $trackLinks = null,
        $clickHookUrl = null,
        $deliveryHookUrl = null
    ): DynamicResponseModel {
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

        return new DynamicResponseModel($this->processRestRequest('PUT', '/server', $body));
    }

    /**
     * Search messages that have been sent using this Server.
     *
     * @param int    $count         How many messages to retrieve at once (defaults to 100)
     * @param int    $offset        How many messages to skip when 'paging' through the massages (defaults to 0)
     * @param string $recipient     Filter by recipient.
     * @param string $fromEmail     Filter by sender email address.
     * @param string $tag           Filter by tag.
     * @param string $subject       Filter by subject.
     * @param string $status        The current status for the outbound messages to return defaults to 'sent'
     * @param string $fromdate      Filter to messages on or after YYYY-MM-DD
     * @param string $todate        Filter to messages on or before YYYY-MM-DD
     * @param string $metadata      An associatative array of key-values that must all match values included in the metadata of matching sent messages.
     * @param string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundMessages(
        $count = 100,
        $offset = 0,
        $recipient = null,
        $fromEmail = null,
        $tag = null,
        $subject = null,
        $status = null,
        $fromdate = null,
        $todate = null,
        $metadata = null,
        $messagestream = null
    ): DynamicResponseModel {
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

        if($metadata != null) {
            foreach($metadata as $key => $value) {
                $query["metadata_$key"] = $value;
            }
        }

        return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound', $query));
    }

    /**
     * Get information related to a specific sent message.
     *
     * @param string $id The ID of the Message for which we want details.
     */
    public function getOutboundMessageDetails($id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/details"));
    }

    /**
     * Get the raw content for a message that was sent.
     * This response
     *
     * @param  string $id The ID of the message for which we want a dump.
     */
    public function getOutboundMessageDump($id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/$id/dump"));
    }

    /**
     * Get statistics for tracked messages, optionally filtering by various open event properties.
     *
     * @param int    $count         The number of open statistics to retrieve in this request.
     * @param int    $offset        The number of statistics to 'skip' when paging through statistics.
     * @param  string $recipient     Filter by recipient.
     * @param  string $tag           Filter by tag.
     * @param  string $clientName    Filter by Email Client name.
     * @param  string $clientCompany Filter by Email Client Company's name.
     * @param  string $clientFamily  Filter by Email Client's Family name.
     * @param  string $osName        Filter by Email Client's Operating System Name.
     * @param  string $osFamily      Filter by Email Client's Operating System's Family.
     * @param  string $osCompany     Filter by Email Client's Operating System's Company.
     * @param  string $platform      Filter by Email Client's Platform Name.
     * @param  string $country       Filter by Country.
     * @param  string $region        Filter by Region.
     * @param  string $city          Filter by City.
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOpenStatistics(
        $count = 100,
        $offset = 0,
        $recipient = null,
        $tag = null,
        $clientName = null,
        $clientCompany = null,
        $clientFamily = null,
        $osName = null,
        $osFamily = null,
        $osCompany = null,
        $platform = null,
        $country = null,
        $region = null,
        $city = null,
        $messagestream = null
    ): DynamicResponseModel {
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

        return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound/opens', $query));
    }

    /**
     * Get statistics for tracked messages, optionally filtering by various click event properties.
     *
     * @param int    $count         The number of click statistics to retrieve in this request.
     * @param int    $offset        The number of statistics to 'skip' when paging through statistics.
     * @param  string $recipient     Filter by recipient.
     * @param  string $tag           Filter by tag.
     * @param  string $clientName    Filter by Email Client name.
     * @param  string $clientCompany Filter by Email Client Company's name.
     * @param  string $clientFamily  Filter by Email Client's Family name.
     * @param  string $osName        Filter by Email Client's Operating System Name.
     * @param  string $osFamily      Filter by Email Client's Operating System's Family.
     * @param  string $osCompany     Filter by Email Client's Operating System's Company.
     * @param  string $platform      Filter by Email Client's Platform Name.
     * @param  string $country       Filter by Country.
     * @param  string $region        Filter by Region.
     * @param  string $city          Filter by City.
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getClickStatistics(
        $count = 100,
        $offset = 0,
        $recipient = null,
        $tag = null,
        $clientName = null,
        $clientCompany = null,
        $clientFamily = null,
        $osName = null,
        $osFamily = null,
        $osCompany = null,
        $platform = null,
        $country = null,
        $region = null,
        $city = null,
        $messagestream = null
    ): DynamicResponseModel {
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

        return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound/clicks', $query));
    }

    /**
     * Get information about individual opens for a sent message.
     *
     * @param  string $id     The ID for the message that we want statistics for.
     * @param int    $count  How many statistics should we retrieve?
     * @param int    $offset How many should we 'skip' when 'paging' through statistics.
     */
    public function getOpenStatisticsForMessage($id, $count = 100, $offset = 0): DynamicResponseModel
    {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;

        return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/opens/$id", $query));
    }

    /**
     * Get information about individual clicks for a sent message.
     *
     * @param  string $id     The ID for the message that we want statistics for.
     * @param int    $count  How many statistics should we retrieve?
     * @param int    $offset How many should we 'skip' when 'paging' through statistics.
     */
    public function getClickStatisticsForMessage($id, $count = 100, $offset = 0): DynamicResponseModel
    {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;

        return new DynamicResponseModel($this->processRestRequest('GET', "/messages/outbound/clicks/$id", $query));
    }

    /**
     * Get an overview of the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundOverviewStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound', $query));
    }

    /**
     * Get send statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundSendStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/sends', $query));
    }

    /**
     * Get bounce statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundBounceStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/bounces', $query));
    }

    /**
     * Get SPAM complaint statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundSpamComplaintStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/spam', $query));
    }

    /**
     * Get bounce statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundTrackedStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/tracked', $query));
    }

    /**
     * Get open statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundOpenStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens', $query));
    }

    /**
     * Get platform statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundPlatformStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/platforms', $query));
    }

    /**
     * Get email client statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundEmailClientStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/emailclients', $query));
    }

    /**
     * Get reading times for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag      Filter by tag.
     * @param  string $fromdate must be of the format 'YYYY-MM-DD'
     * @param  string $todate   must be of the format 'YYYY-MM-DD'
     */
    public function getOutboundReadTimeStatistics($tag = null, $fromdate = null, $todate = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/opens/readtimes', $query));
    }

    /**
     * Get click statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundClickStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/clicks', $query));
    }

    /**
     * Get information about which browsers were used to open tracked links for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundClickBrowserFamilyStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/clicks/browserfamilies', $query));
    }

    /**
     * Get information about which browsers platforms (Desktop, Mobile, etc.) were used to open
     * tracked links for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundClickBrowserPlatformStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/clicks/platforms', $query));
    }

    /**
     * Get information about part of the message (HTML or Text)
     * tracked links were clicked from in messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param  string $tag           Filter by tag.
     * @param  string $fromdate      must be of the format 'YYYY-MM-DD'
     * @param  string $todate        must be of the format 'YYYY-MM-DD'
     * @param  string $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional stream will be used.
     */
    public function getOutboundClickLocationStatistics($tag = null, $fromdate = null, $todate = null, $messagestream = null): DynamicResponseModel
    {
        $query = [];

        $query['tag'] = $tag;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/stats/outbound/clicks/location', $query));
    }

    /**
     * Get information about a specific webhook configuration.
     *
     * @param int $id The Id of the webhook configuration you wish to retrieve.
     */
    public function getWebhookConfiguration($id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', "/webhooks/$id"));
    }

    /**
     * Get all webhook configurations associated with the Server.
     *
     * @param string $messageStream Optional message stream to filter results by. If not provided, all configurations for the server will be returned.
     */
    public function getWebhookConfigurations($messageStream = null): DynamicResponseModel
    {
        $query = [];
        $query['MessageStream'] = $messageStream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/webhooks', $query));
    }

    /**
     * Delete a webhook configuration.
     *
     * @param int $id The Id of the webhook configuration you wish to delete.
     */
    public function deleteWebhookConfiguration($id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('DELETE', "/webhooks/$id"));
    }

    /**
     * Create a webhook configuration.
     *
     * @param string                       $url           The webhook URL.
     * @param string                       $messageStream Message stream this configuration should belong to. If not provided, it will belong to the default transactional stream.
     * @param HttpAuth                     $httpAuth      Optional Basic HTTP Authentication.
     * @param array                        $httpHeaders   Optional list of custom HTTP headers.
     * @param WebhookConfigurationTriggers $triggers      Optional triggers for this webhook configuration.
     */
    public function createWebhookConfiguration($url, $messageStream = null, $httpAuth = null, $httpHeaders = null, $triggers = null): DynamicResponseModel
    {
        $body = [];
        $body['Url'] = $url;
        $body['MessageStream'] = $messageStream;
        $body['HttpAuth'] = $httpAuth;
        $body['HttpHeaders'] = $this->fixHeaders($httpHeaders);
        $body['Triggers'] = $triggers;

        return new DynamicResponseModel($this->processRestRequest('POST', '/webhooks', $body));
    }

    /**
     * Edit a webhook configuration.
     * Any parameters passed with NULL will be ignored (their existing values will not be modified).
     *
     * @param int                          $id          The Id of the webhook configuration you wish to edit.
     * @param string                       $url         Optional webhook URL.
     * @param HttpAuth                     $httpAuth    Optional Basic HTTP Authentication.
     * @param array                        $httpHeaders Optional list of custom HTTP headers.
     * @param WebhookConfigurationTriggers $triggers    Optional triggers for this webhook configuration.
     */
    public function editWebhookConfiguration($id, $url = null, $httpAuth = null, $httpHeaders = null, $triggers = null): DynamicResponseModel
    {
        $body = [];
        $body['Url'] = $url;
        $body['HttpAuth'] = $httpAuth;
        $body['HttpHeaders'] = $this->fixHeaders($httpHeaders);
        $body['Triggers'] = $triggers;

        return new DynamicResponseModel($this->processRestRequest('PUT', "/webhooks/$id", $body));
    }
}
