<?php

declare(strict_types=1);

namespace Postmark;

use Postmark\ClientBehaviour\Bounces;
use Postmark\ClientBehaviour\InboundMessages;
use Postmark\ClientBehaviour\MessageStreams;
use Postmark\ClientBehaviour\OutboundMessages;
use Postmark\ClientBehaviour\PostmarkClientBase;
use Postmark\ClientBehaviour\Statistics;
use Postmark\ClientBehaviour\Suppressions;
use Postmark\ClientBehaviour\Templates;
use Postmark\ClientBehaviour\Webhooks;
use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Header;
use Postmark\Models\PostmarkAttachment;

use function is_int;
use function strtolower;

/**
 * @link PostmarkAttachment
 *
 * @psalm-type Attachments = list<PostmarkAttachment>|null
 * @psalm-type HeaderList = array<string, scalar|null>
 * @psalm-type MetaData = array<string, scalar>
 * @psalm-type EmailMessage = array{
 *     From: non-empty-string,
 *     To: non-empty-string,
 *     Cc?: non-empty-string|null,
 *     Bcc?: non-empty-string|null,
 *     Subject: non-empty-string,
 *     Tag?: non-empty-string|null,
 *     HtmlBody?: non-empty-string|null,
 *     TextBody?: non-empty-string|null,
 *     ReplyTo?: non-empty-string|null,
 *     Metadata?: MetaData|null,
 *     Headers?: HeaderList,
 *     TrackOpens?: bool|null,
 *     TrackLinks?: string|null,
 *     MessageStream?: string|null
 * }
 * @psalm-type EmailBatch = list<EmailMessage>
 * @psalm-type TemplateModelObject = array<string, scalar>
 * @psalm-type TemplateModel = array<string, scalar|TemplateModelObject>
 * @psalm-type TemplateMessage = array{
 *     TemplateId?: int,
 *     TemplateAlias?: string,
 *     TemplateModel: TemplateModel,
 *     InlineCss?: bool,
 *     From: non-empty-string,
 *     To: non-empty-string,
 *     Cc?: non-empty-string|null,
 *     Bcc?: non-empty-string|null,
 *     Tag?: non-empty-string|null,
 *     ReplyTo?: non-empty-string|null,
 *     Metadata?: MetaData|null,
 *     Headers?: HeaderList,
 *     TrackOpens?: bool|null,
 *     TrackLinks?: string|null,
 *     MessageStream?: string|null
 * }
 * @psalm-type TemplateBatch = list<TemplateMessage>
 */
final class PostmarkClient extends PostmarkClientBase
{
    use Bounces;
    use InboundMessages;
    use MessageStreams;
    use OutboundMessages;
    use Statistics;
    use Suppressions;
    use Templates;
    use Webhooks;

    private const AUTH_HEADER_NAME = 'X-Postmark-Server-Token';

    protected function authorizationHeaderName(): string
    {
        return self::AUTH_HEADER_NAME;
    }

    /**
     * Send an email.
     *
     * @param string          $from          The sender of the email. (Your account must have an associated Sender
     *                                       Signature for the address used.)
     * @param string          $to            The recipient of the email.
     * @param string          $subject       The subject of the email.
     * @param string|null     $htmlBody      The HTML content of the message, optional if Text Body is specified.
     * @param string|null     $textBody      The text content of the message, optional if HTML Body is specified.
     * @param string|null     $tag           A tag associated with this message, useful for classifying sent messages.
     * @param bool|null       $trackOpens    True if you want Postmark to track opens of HTML emails.
     * @param string|null     $replyTo       Reply to email address.
     * @param string|null     $cc            Carbon Copy recipients, comma-separated
     * @param string|null     $bcc           Blind Carbon Copy recipients, comma-separated.
     * @param HeaderList|null $headers       Headers to be included with the sent email message.
     * @param Attachments     $attachments   An array of PostmarkAttachment objects.
     * @param string|null     $trackLinks    Can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable link
     *                                       tracking.
     * @param MetaData|null   $metadata      Add metadata to the message. The metadata is an associative array, and
     *                                       values will be evaluated as strings by Postmark.
     * @param string|null     $messageStream The message stream used to send this message. If not provided, the default
     *                                       transactional stream "outbound" will be used.
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
     * @param string          $from              The sender of the email. (Your account must have an associated Sender
     *                                           Signature for the address used.)
     * @param string          $to                The recipient of the email.
     * @param int|string      $templateIdOrAlias The ID or alias of the template to use to generate the content of this
     *                                           message.
     * @param TemplateModel   $templateModel     The values to combine with the Templated content.
     * @param bool            $inlineCss         If the template contains an HTMLBody, CSS is automatically inlined, you
     *                                           may opt-out of this by passing 'false' for this parameter.
     * @param string|null     $tag               A tag associated with this message, useful for classifying sent
     *                                           messages.
     * @param bool|null       $trackOpens        True if you want Postmark to track opens of HTML emails.
     * @param string|null     $replyTo           Reply to email address.
     * @param string|null     $cc                Carbon Copy recipients, comma-separated
     * @param string|null     $bcc               Blind Carbon Copy recipients, comma-separated.
     * @param HeaderList|null $headers           Headers to be included with the sent email message.
     * @param Attachments     $attachments       An array of PostmarkAttachment objects.
     * @param string|null     $trackLinks        Can be any of "None", "HtmlAndText", "HtmlOnly", "TextOnly" to enable
     *                                           link tracking.
     * @param MetaData|null   $metadata          Add metadata to the message. The metadata is an associative array , and
     *                                           values will be evaluated as strings by Postmark.
     * @param string|null     $messageStream     The message stream used to send this message. If not provided, the
     *                                           default transactional stream "outbound" will be used.
     * @psalm-param TemplateModel $templateModel
     * @psalm-param HeaderList|null $headers
     */
    public function sendEmailWithTemplate(
        string $from,
        string $to,
        $templateIdOrAlias,
        array $templateModel,
        bool $inlineCss = true,
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
     * Send multiple emails as a batch
     *
     * Each email is an associative array of values, but note that the 'Attachments'
     * key must be an array of 'PostmarkAttachment' objects if you intend to send
     * attachments with an email.
     *
     * @param EmailBatch $emailBatch An array of emails to be sent in one batch.
     */
    public function sendEmailBatch($emailBatch = []): DynamicResponseModel
    {
        $final = [];

        foreach ($emailBatch as $email) {
            foreach ($email as $key => $emailValue) {
                if (strtolower($key) !== 'headers') {
                    continue;
                }

                /** @psalm-var HeaderList $emailValue */
                $email[$key] = Header::listFromArray($emailValue);
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
     * @param TemplateBatch $emailBatch An array of emails to be sent in one batch.
     * @psalm-param TemplateBatch $emailBatch
     */
    public function sendEmailBatchWithTemplate(array $emailBatch = []): DynamicResponseModel
    {
        $final = [];

        foreach ($emailBatch as $email) {
            foreach ($email as $key => $emailValue) {
                if (strtolower($key) !== 'headers') {
                    continue;
                }

                /** @psalm-var HeaderList $emailValue */
                $email[$key] = Header::listFromArray($emailValue);
            }

            $final[] = $email;
        }

        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/email/batchWithTemplates', ['Messages' => $final])
        );
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
     * @param string|null $name                 Set the name of the server.
     * @param string|null $color                Set the color for the server in the Postmark WebUI (must be: 'purple',
     *                                          'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param bool|null   $rawEmailEnabled      Enable raw email to be sent with inbound.
     * @param bool|null   $smtpApiActivated     Specifies whether or not SMTP is enabled on this server.
     * @param string|null $inboundHookUrl       URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl        URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl          URL to POST to everytime an open event occurs.
     * @param bool|null   $postFirstOpenOnly    If set to true, only the first open by a particular recipient will
     *                                          initiate the open webhook. Any subsequent opens of the same email by
     *                                          the same recipient will not initiate the webhook.
     * @param bool|null   $trackOpens           Indicates if all emails being sent through this server have open
     *                                          tracking enabled.
     * @param string|null $inboundDomain        Inbound domain for MX setup.
     * @param int|null    $inboundSpamThreshold The maximum spam score for an inbound message before it's
     *                                          blocked (range from 0-30).
     * @param string|null $trackLinks           Indicates if all emails being sent through this server have
     *                                          link tracking enabled.
     * @param string|null $clickHookUrl         URL to POST to everytime an click event occurs.
     * @param string|null $deliveryHookUrl      URL to POST to everytime an click event occurs.
     */
    public function editServer(
        ?string $name = null,
        ?string $color = null,
        ?bool $rawEmailEnabled = null,
        ?bool $smtpApiActivated = null,
        ?string $inboundHookUrl = null,
        ?string $bounceHookUrl = null,
        ?string $openHookUrl = null,
        ?bool $postFirstOpenOnly = null,
        ?bool $trackOpens = null,
        ?string $inboundDomain = null,
        ?int $inboundSpamThreshold = null,
        ?string $trackLinks = null,
        ?string $clickHookUrl = null,
        ?string $deliveryHookUrl = null
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
}
