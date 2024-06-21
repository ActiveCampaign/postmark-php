<?php

namespace Postmark;

use Postmark\Models\DataRemovalRequestResponse;
use Postmark\Models\PostmarkDomain;
use Postmark\Models\PostmarkDomainDetails;
use Postmark\Models\PostmarkDomainList;
use Postmark\Models\PostmarkException;
use Postmark\Models\PostmarkResponse;
use Postmark\Models\PostmarkSenderSignature;
use Postmark\Models\PostmarkSenderSignatureList;
use Postmark\Models\PostmarkServer;
use Postmark\Models\PostmarkServerList;

/**
 * The PostmarkAdminClient allows users to access and modify
 *  "Account-wide" settings. At this time the API supports
 *  management of the "Sender Signatures", "Domains", and "Servers.".
 */
class PostmarkAdminClient extends PostmarkClientBase
{
    /**
     * Create a new PostmarkAdminClient.
     *
     * @param string $accountToken The Account Token used to access the Admin API.
     *                             This token is NOT the same as a Server Token. You can get your account token
     *                             from this page: https://postmarkapp.com/account/edit
     * @param int    $timeout      the timeout, in seconds, that API calls should wait before throwing an exception
     */
    public function __construct(string $accountToken, int $timeout = 60)
    {
        parent::__construct($accountToken, 'X-Postmark-Account-Token', $timeout);
    }

    /**
     * Request a given server by ID.
     *
     * @param int $id the Id for the server you wish to retrieve
     *
     * @throws PostmarkException
     */
    public function getServer(int $id): PostmarkServer
    {
        return new PostmarkServer((array) $this->processRestRequest('GET', "/servers/{$id}"));
    }

    /**
     * Get a list of all servers configured on the account.
     *
     * @param int         $count  the number of servers to retrieve in the request, defaults to 100
     * @param int         $offset the number of servers to "skip" when paging through lists of servers
     * @param null|string $name   filter by server name
     *
     * @throws PostmarkException
     */
    public function listServers(int $count = 100, int $offset = 0, string $name = null): PostmarkServerList
    {
        $query = [];
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['name'] = $name;

        return new PostmarkServerList((array) $this->processRestRequest('GET', '/servers/', $query));
    }

    /**
     * Delete a Server used for sending/receiving mail. NOTE: To protect your account, you'll need to
     * contact support and request that they enable this feature on your account before you can use this
     * client to delete Servers.
     *
     * @param int $id the ID of the Server to delete
     *
     * @throws PostmarkException
     */
    public function deleteServer(int $id): PostmarkResponse
    {
        return new PostmarkResponse((array) $this->processRestRequest('DELETE', "/servers/{$id}"));
    }

    /**
     * Modify an existing Server. Any parameters passed with NULL will be
     * ignored (their existing values will not be modified).
     *
     * @param int         $id                      the ID of the Server we wish to modify
     * @param null|string $name                    set the name of the server
     * @param null|string $color                   Set the color for the server in the Postmark WebUI (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param null|bool   $rawEmailEnabled         enable raw email to be sent with inbound
     * @param null|bool   $smtpApiActivated        specifies whether or not SMTP is enabled on this server
     * @param null|string $inboundHookUrl          URL to POST to everytime an inbound event occurs
     * @param null|string $bounceHookUrl           URL to POST to everytime a bounce event occurs
     * @param null|string $openHookUrl             URL to POST to everytime an open event occurs
     * @param null|bool   $postFirstOpenOnly       If set to true, only the first open by a particular recipient will initiate the open webhook. Any subsequent opens of the same email by the same recipient will not initiate the webhook.
     * @param null|bool   $trackOpens              indicates if all emails being sent through this server have open tracking enabled
     * @param null|string $inboundDomain           inbound domain for MX setup
     * @param null|int    $inboundSpamThreshold    the maximum spam score for an inbound message before it's blocked (range from 0-30)
     * @param null|string $trackLinks              indicates if all emails being sent through this server have link tracking enabled
     * @param null|string $clickHookUrl            URL to POST to everytime an click event occurs
     * @param null|string $deliveryHookUrl         URL to POST to everytime an click event occurs
     * @param null|string $enableSmtpApiErrorHooks specifies whether or not SMTP API Errors will be included with bounce webhooks
     *
     * @throws PostmarkException
     */
    public function editServer(
        int $id,
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
        string $deliveryHookUrl = null,
        string $enableSmtpApiErrorHooks = null
    ): PostmarkServer {
        $body = [];
        $body['name'] = $name;
        $body['color'] = $color;
        $body['rawEmailEnabled'] = $rawEmailEnabled;
        $body['smtpApiActivated'] = $smtpApiActivated;
        $body['inboundHookUrl'] = $inboundHookUrl;
        $body['bounceHookUrl'] = $bounceHookUrl;
        $body['openHookUrl'] = $openHookUrl;
        $body['postFirstOpenOnly'] = $postFirstOpenOnly;
        $body['trackOpens'] = $trackOpens;
        $body['inboundDomain'] = $inboundDomain;
        $body['inboundSpamThreshold'] = $inboundSpamThreshold;
        $body['trackLinks'] = $trackLinks;
        $body['ClickHookUrl'] = $clickHookUrl;
        $body['DeliveryHookUrl'] = $deliveryHookUrl;
        $body['EnableSmtpApiErrorHooks'] = $enableSmtpApiErrorHooks;

        $response = new PostmarkServer((array) $this->processRestRequest('PUT', "/servers/{$id}", $body));
        $response->setID($id);

        return $response;
    }

    /**
     * Create a new Server. Any parameters passed with NULL will be
     * ignored (their default values will be used).
     *
     * @param string      $name                    set the name of the server
     * @param null|string $color                   Set the color for the server in the Postmark WebUI (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param null|bool   $rawEmailEnabled         enable raw email to be sent with inbound
     * @param null|bool   $smtpApiActivated        specifies whether or not SMTP is enabled on this server
     * @param null|string $inboundHookUrl          URL to POST to everytime an inbound event occurs
     * @param null|string $bounceHookUrl           URL to POST to everytime a bounce event occurs
     * @param null|string $openHookUrl             URL to POST to everytime an open event occurs
     * @param null|bool   $postFirstOpenOnly       If set to true, only the first open by a particular recipient will initiate the open webhook. Any subsequent opens of the same email by the same recipient will not initiate the webhook.
     * @param null|bool   $trackOpens              indicates if all emails being sent through this server have open tracking enabled
     * @param null|string $inboundDomain           inbound domain for MX setup
     * @param null|int    $inboundSpamThreshold    the maximum spam score for an inbound message before it's blocked (range from 0-30)
     * @param null|string $trackLinks              indicates if all emails being sent through this server have link tracking enabled
     * @param null|string $clickHookUrl            URL to POST to everytime an click event occurs
     * @param null|string $deliveryHookUrl         URL to POST to everytime an click event occurs
     * @param null|string $enableSmtpApiErrorHooks specifies whether or not SMTP API Errors will be included with bounce webhooks
     *
     * @throws PostmarkException
     */
    public function createServer(
        string $name,
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
        string $deliveryHookUrl = null,
        string $enableSmtpApiErrorHooks = null
    ): PostmarkServer {
        $body = [];
        $body['name'] = $name;
        $body['color'] = $color;
        $body['rawEmailEnabled'] = $rawEmailEnabled;
        $body['smtpApiActivated'] = $smtpApiActivated;
        $body['inboundHookUrl'] = $inboundHookUrl;
        $body['bounceHookUrl'] = $bounceHookUrl;
        $body['openHookUrl'] = $openHookUrl;
        $body['postFirstOpenOnly'] = $postFirstOpenOnly;
        $body['trackOpens'] = $trackOpens;
        $body['inboundDomain'] = $inboundDomain;
        $body['inboundSpamThreshold'] = $inboundSpamThreshold;
        $body['trackLinks'] = $trackLinks;
        $body['ClickHookUrl'] = $clickHookUrl;
        $body['DeliveryHookUrl'] = $deliveryHookUrl;
        $body['EnableSmtpApiErrorHooks'] = $enableSmtpApiErrorHooks;

        return new PostmarkServer((array) $this->processRestRequest('POST', '/servers/', $body));
    }

    /**
     * Get a "page" of Sender Signatures.
     *
     * @param int $count  the number of Sender Signatures to retrieve with this request
     * @param int $offset the number of Sender Signatures to 'skip' when 'paging' through them
     *
     * @throws PostmarkException
     */
    public function listSenderSignatures(int $count = 100, int $offset = 0): PostmarkSenderSignatureList
    {
        $query = [];
        $query['count'] = $count;
        $query['offset'] = $offset;

        return new PostmarkSenderSignatureList((array) $this->processRestRequest('GET', '/senders/', $query));
    }

    /**
     * Get information for a specific Sender Signature.
     *
     * @param int $id the ID for the Sender Signature you wish to retrieve
     *
     * @throws PostmarkException
     */
    public function getSenderSignature(int $id): PostmarkSenderSignature
    {
        return new PostmarkSenderSignature((array) $this->processRestRequest('GET', "/senders/{$id}"));
    }

    /**
     * Create a new Sender Signature for a given email address. Note that you will need to
     * "verify" this Sender Signature by following a link that will be emailed to the "fromEmail"
     * address specified when calling this method.
     *
     * @param string      $fromEmail        The email address for the Sender Signature
     * @param string      $name             the name of the Sender Signature
     * @param null|string $replyToEmail     the reply-to email address for the Sender Signature
     * @param null|string $returnPathDomain the custom Return-Path domain for the Sender Signature
     *
     * @throws PostmarkException
     */
    public function createSenderSignature(string $fromEmail, string $name, string $replyToEmail = null, string $returnPathDomain = null, string $confirmationPersonalNote = null): PostmarkSenderSignature
    {
        $body = [];
        $body['fromEmail'] = $fromEmail;
        $body['name'] = $name;
        $body['replyToEmail'] = $replyToEmail;
        $body['returnPathDomain'] = $returnPathDomain;
        $body['confirmationPersonalNote'] = $confirmationPersonalNote;

        return new PostmarkSenderSignature((array) $this->processRestRequest('POST', '/senders/', $body));
    }

    /**
     * Alter the defaults for a Sender Signature.
     *
     * @param int         $id               the ID for the Sender Signature we wish to modify
     * @param null|string $name             the name of the Sender Signature
     * @param null|string $replyToEmail     the reply-to email address for the Sender Signature
     * @param null|string $returnPathDomain the custom Return-Path domain for the Sender Signature
     *
     * @throws PostmarkException
     */
    public function editSenderSignature(int $id, string $name = null, string $replyToEmail = null, string $returnPathDomain = null, string $confirmationPersonalNote = null): PostmarkSenderSignature
    {
        $body = [];
        $body['name'] = $name;
        $body['replyToEmail'] = $replyToEmail;
        $body['returnPathDomain'] = $returnPathDomain;
        $body['confirmationPersonalNote'] = $confirmationPersonalNote;

        return new PostmarkSenderSignature((array) $this->processRestRequest('PUT', "/senders/{$id}", $body));
    }

    /**
     * Delete a Sender Signature with the given ID.
     *
     * @param int $id the ID for the Sender Signature we wish to delete
     *
     * @throws PostmarkException
     */
    public function deleteSenderSignature(int $id): PostmarkResponse
    {
        return new PostmarkResponse((array) $this->processRestRequest('DELETE', "/senders/{$id}"));
    }

    /**
     * Cause a new verification email to be sent for an existing (unverified) Sender Signature.
     * Sender Signatures require verification before they may be used to send email through the Postmark API.
     *
     * @param int $id the ID for the Sender Signature to which we wish to resend a verification email
     *
     * @throws PostmarkException
     */
    public function resendSenderSignatureConfirmation(int $id): PostmarkResponse
    {
        return new PostmarkResponse((array) $this->processRestRequest('POST', "/senders/{$id}/resend"));
    }

    /**
     * Get a "page" of Domains.
     *
     * @param int $count  the number of Domains to retrieve with this request
     * @param int $offset the number of Domains to 'skip' when 'paging' through them
     *
     * @throws PostmarkException
     */
    public function listDomains(int $count = 100, int $offset = 0): PostmarkDomainList
    {
        $query = [];
        $query['count'] = $count;
        $query['offset'] = $offset;

        return new PostmarkDomainList((array) $this->processRestRequest('GET', '/domains/', $query));
    }

    /**
     * Get information for a specific Domain.
     *
     * @param int $id the ID for the Domains you wish to retrieve
     *
     * @throws PostmarkException
     */
    public function getDomain(int $id): PostmarkDomainDetails
    {
        return new PostmarkDomainDetails((array) $this->processRestRequest('GET', "/domains/{$id}"));
    }

    /**
     * Create a new Domain with the given Name.
     *
     * @param string      $name             the name of the Domain
     * @param null|string $returnPathDomain the custom Return-Path domain for the Sender Signature
     *
     * @throws PostmarkException
     */
    public function createDomain(string $name, string $returnPathDomain = null): PostmarkDomainDetails
    {
        $body = [];
        $body['name'] = $name;
        $body['returnPathDomain'] = $returnPathDomain;

        return new PostmarkDomainDetails((array) $this->processRestRequest('POST', '/domains/', $body));
    }

    /**
     * Alter the properties of a Domain.
     *
     * @param int         $id                   the ID for the Domain we wish to modify
     * @param null|string $returnPathDomain     the custom Return-Path domain for the Domain
     * @param null|mixed  $CustomTrackingDomain
     *
     * @throws PostmarkException
     */
    public function editDomain(int $id, string $returnPathDomain = null, $CustomTrackingDomain = null): PostmarkDomainDetails
    {
        $body = [];
        $body['returnPathDomain'] = $returnPathDomain;
        $body['CustomTrackingDomain'] = $CustomTrackingDomain;

        return new PostmarkDomainDetails((array) $this->processRestRequest('PUT', "/domains/{$id}", $body));
    }

    /**
     * Verify Custom Tracking.
     *
     * @param int $id the ID for the Domain we wish to verify
     *
     * @throws PostmarkException
     */
    public function verifyCustomTracking(int $id): PostmarkDomainDetails
    {
        $body = [];

        return new PostmarkDomainDetails((array) $this->processRestRequest('PUT', "/domains/{$id}/verifyCustomTracking", $body));
    }

    /**
     * Delete a Domain with the given ID.
     *
     * @param int $id the ID for the Domain we wish to delete
     *
     * @throws PostmarkException
     */
    public function deleteDomain(int $id): PostmarkResponse
    {
        return new PostmarkResponse((array) $this->processRestRequest('DELETE', "/domains/{$id}"));
    }

    /**
     * Request that the Postmark API verify DKIM keys associated
     * with the Domain.
     *
     * @param int $id the ID of the Domain we wish to verify DKIM keys on
     *
     * @throws PostmarkException
     */
    public function verifyDKIM(int $id): PostmarkDomainDetails
    {
        return new PostmarkDomainDetails((array) $this->processRestRequest('PUT', "/domains/{$id}/verifyDkim"));
    }

    /**
     * Request that the Postmark API verify Return-Path DNS records associated
     * with the Domain.
     *
     * @param int $id the ID of the Domain we wish to verify Return-Path DNS record on
     *
     * @throws PostmarkException
     */
    public function verifyReturnPath(int $id): PostmarkDomainDetails
    {
        return new PostmarkDomainDetails((array) $this->processRestRequest('PUT', "/domains/{$id}/verifyReturnPath"));
    }

    /**
     * Rotate DKIM keys associated with the Domain. This key must be added
     * to your DNS records. Including DKIM is not required, but is recommended. For more information
     * on DKIM and its purpose, see http://www.dkim.org/.
     *
     * @param int $id the ID for the Domain for which we wish to get an updated DKIM configuration
     *
     * @throws PostmarkException
     */
    public function rotateDKIMForDomain(int $id): PostmarkDomain
    {
        return new PostmarkDomain((array) $this->processRestRequest('POST', "/domains/{$id}/rotatedkim"));
    }

    /**
     * This endpoint allows you to erase recipient data from a specific account - for example when you’re processing
     * Data Subject Requests (DSR) under GDPR or CCPA.
     *
     * @param string $requestedBy         The email address of the user that is making the request
     * @param string $requestedFor        the email address of the recipient who's asking for their data to be removed
     * @param bool   $notifyWhenCompleted Specifies whether the RequestedBy email address is notified when the data
     *                                    removal request is complete
     *
     * @throws PostmarkException
     */
    public function createDataRemovalRequest(
        string $requestedBy,
        string $requestedFor,
        bool $notifyWhenCompleted
    ): DataRemovalRequestResponse {
        $body = [];
        $body['RequestedBy'] = $requestedBy;
        $body['RequestedFor'] = $requestedFor;
        $body['NotifyWhenCompleted'] = $notifyWhenCompleted;

        return new DataRemovalRequestResponse((array) $this->processRestRequest('POST', '/data-removals', $body));
    }

    /**
     * Review the status of your data removal requests.
     *
     * @param int $id ID of data removal request
     *
     * @throws PostmarkException
     */
    public function getDataRemoval(int $id): DataRemovalRequestResponse
    {
        return new DataRemovalRequestResponse((array) $this->processRestRequest('GET', sprintf('/data-removals/%s', $id)));
    }
}
