<?php

namespace Postmark;

use Postmark\PostmarkClientBase as PostmarkClientBase;
use Postmark\Models\DataRemovalRequestResponse as DataRemovalRequestResponse;
use Postmark\Models\PostmarkDomain;
use Postmark\Models\PostmarkDomainDetails;
use Postmark\Models\PostmarkDomainList;
use Postmark\Models\PostmarkResponse;
use Postmark\Models\PostmarkSenderSignature;
use Postmark\Models\PostmarkSenderSignatureList;
use Postmark\Models\PostmarkServer;
use Postmark\Models\PostmarkServerList;

/**
 * The PostmarkAdminClient allows users to access and modify
 *  "Account-wide" settings. At this time the API supports
 *  management of the "Sender Signatures", "Domains", and "Servers."
 */
class PostmarkAdminClient extends PostmarkClientBase
{

    /**
     * Create a new PostmarkAdminClient.
     *
     * @param string $accountToken The Account Token used to access the Admin API.
     * This token is NOT the same as a Server Token. You can get your account token
     * from this page: https://postmarkapp.com/account/edit
     *
     * @param integer $timeout The timeout, in seconds, that API calls should wait before throwing an exception.
     */
    function __construct(string $accountToken, int $timeout = 60)
    {
        parent::__construct($accountToken, "X-Postmark-Account-Token", $timeout);
    }

    /**
     * Request a given server by ID.
     *
     * @param int $id The Id for the server you wish to retrieve.
     * @return PostmarkServer
     */
    function getServer(int $id): PostmarkServer
    {
        return new PostmarkServer($this->processRestRequest('GET', "/servers/$id"));
    }

    /**
     * Get a list of all servers configured on the account.
     *
     * @param integer $count The number of servers to retrieve in the request, defaults to 100.
     * @param integer $offset The number of servers to "skip" when paging through lists of servers.
     * @param string|null $name Filter by server name.
     * @return PostmarkServerList
     */
    function listServers(int $count = 100, int $offset = 0, string $name = NULL): PostmarkServerList
    {
        $query = array();
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['name'] = $name;

        return new PostmarkServerList($this->processRestRequest('GET', '/servers/', $query));
    }

    /**
     * Delete a Server used for sending/receiving mail. NOTE: To protect your account, you'll need to
     * contact support and request that they enable this feature on your account before you can use this
     * client to delete Servers.
     *
     * @param integer $id The ID of the Server to delete.
     * @return PostmarkResponse
     */
    function deleteServer(int $id): PostmarkResponse
    {
        return new PostmarkResponse($this->processRestRequest('DELETE', "/servers/$id"));
    }

    /**
     * Modify an existing Server. Any parameters passed with NULL will be
     * ignored (their existing values will not be modified).
     *
     * @param integer $id The ID of the Server we wish to modify.
     * @param string|null $name Set the name of the server.
     * @param string|null $color Set the color for the server in the Postmark WebUI (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param bool|null $rawEmailEnabled Enable raw email to be sent with inbound.
     * @param bool|null $smtpApiActivated Specifies whether or not SMTP is enabled on this server.
     * @param string|null $inboundHookUrl URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl URL to POST to everytime an open event occurs.
     * @param bool|null $postFirstOpenOnly If set to true, only the first open by a particular recipient will initiate the open webhook. Any subsequent opens of the same email by the same recipient will not initiate the webhook.
     * @param bool|null $trackOpens Indicates if all emails being sent through this server have open tracking enabled.
     * @param string|null $inboundDomain Inbound domain for MX setup.
     * @param integer|null $inboundSpamThreshold The maximum spam score for an inbound message before it's blocked (range from 0-30).
     * @param string|null $trackLinks Indicates if all emails being sent through this server have link tracking enabled.
     * @param string|null $clickHookUrl URL to POST to everytime an click event occurs.
     * @param string|null $deliveryHookUrl URL to POST to everytime an click event occurs.
     * @param string|null $enableSmtpApiErrorHooks Specifies whether or not SMTP API Errors will be included with bounce webhooks.
     * @return PostmarkServer
     */
    function editServer(
        int    $id,
        string $name = NULL,
        string $color = NULL,
        bool   $rawEmailEnabled = NULL,
        bool   $smtpApiActivated = NULL,
        string $inboundHookUrl = NULL,
        string $bounceHookUrl = NULL,
        string $openHookUrl = NULL,
        bool   $postFirstOpenOnly = NULL,
        bool   $trackOpens = NULL,
        string $inboundDomain = NULL,
        int    $inboundSpamThreshold = NULL,
        string $trackLinks = NULL,
        string $clickHookUrl = NULL,
        string $deliveryHookUrl = NULL,
        string $enableSmtpApiErrorHooks = NULL): PostmarkServer
    {

        $body = array();
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
        $body["ClickHookUrl"] = $clickHookUrl;
        $body["DeliveryHookUrl"] = $deliveryHookUrl;
        $body["EnableSmtpApiErrorHooks"] = $enableSmtpApiErrorHooks;

        $response = new PostmarkServer($this->processRestRequest('PUT', "/servers/$id", $body));
        $response->setID($id);
        return $response;
    }

    /**
     * Create a new Server. Any parameters passed with NULL will be
     * ignored (their default values will be used).
     *
     * @param string $name Set the name of the server.
     * @param string|null $color Set the color for the server in the Postmark WebUI (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param bool|null $rawEmailEnabled Enable raw email to be sent with inbound.
     * @param bool|null $smtpApiActivated Specifies whether or not SMTP is enabled on this server.
     * @param string|null $inboundHookUrl URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl URL to POST to everytime an open event occurs.
     * @param bool|null $postFirstOpenOnly If set to true, only the first open by a particular recipient will initiate the open webhook. Any subsequent opens of the same email by the same recipient will not initiate the webhook.
     * @param bool|null $trackOpens Indicates if all emails being sent through this server have open tracking enabled.
     * @param string|null $inboundDomain Inbound domain for MX setup.
     * @param integer|null $inboundSpamThreshold The maximum spam score for an inbound message before it's blocked (range from 0-30).
     * @param string|null $trackLinks Indicates if all emails being sent through this server have link tracking enabled.
     * @param string|null $clickHookUrl URL to POST to everytime an click event occurs.
     * @param string|null $deliveryHookUrl URL to POST to everytime an click event occurs.
     * @param string|null $enableSmtpApiErrorHooks Specifies whether or not SMTP API Errors will be included with bounce webhooks.
     * @return PostmarkServer
     */
    function createServer(
        string $name,
        string $color = NULL,
        bool   $rawEmailEnabled = NULL,
        bool   $smtpApiActivated = NULL,
        string $inboundHookUrl = NULL,
        string $bounceHookUrl = NULL,
        string $openHookUrl = NULL,
        bool   $postFirstOpenOnly = NULL,
        bool   $trackOpens = NULL,
        string $inboundDomain = NULL,
        int    $inboundSpamThreshold = NULL,
        string $trackLinks = NULL,
        string $clickHookUrl = NULL,
        string $deliveryHookUrl = NULL,
        string $enableSmtpApiErrorHooks = NULL): PostmarkServer
    {

        $body = array();
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
        $body["ClickHookUrl"] = $clickHookUrl;
        $body["DeliveryHookUrl"] = $deliveryHookUrl;
        $body["EnableSmtpApiErrorHooks"] = $enableSmtpApiErrorHooks;

        return new PostmarkServer($this->processRestRequest('POST', '/servers/', $body));
    }

    /**
     * Get a "page" of Sender Signatures.
     *
     * @param integer $count The number of Sender Signatures to retrieve with this request.
     *  param  integer $offset The number of Sender Signatures to 'skip' when 'paging' through them.
     * @param int $offset
     * @return PostmarkSenderSignatureList
     */
    function listSenderSignatures(int $count = 100, int $offset = 0): PostmarkSenderSignatureList
    {
        $query = array();
        $query['count'] = $count;
        $query['offset'] = $offset;

        return new PostmarkSenderSignatureList($this->processRestRequest('GET', '/senders/', $query));
    }

    /**
     * Get information for a specific Sender Signature.
     *
     * @param integer $id The ID for the Sender Signature you wish to retrieve.
     * @return PostmarkSenderSignature
     */
    function getSenderSignature(int $id): PostmarkSenderSignature
    {
        return new PostmarkSenderSignature($this->processRestRequest('GET', "/senders/$id"));
    }

    /**
     * Create a new Sender Signature for a given email address. Note that you will need to
     * "verify" this Sender Signature by following a link that will be emailed to the "fromEmail"
     * address specified when calling this method.
     *
     * @param string $fromEmail The email address for the Sender Signature
     * @param string $name The name of the Sender Signature.
     * @param string|null $replyToEmail The reply-to email address for the Sender Signature.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     * @return PostmarkSenderSignature
     */
    function createSenderSignature(string $fromEmail, string $name, string $replyToEmail = NULL, string $returnPathDomain = NULL): PostmarkSenderSignature
    {
        $body = array();
        $body['fromEmail'] = $fromEmail;
        $body['name'] = $name;
        $body['replyToEmail'] = $replyToEmail;
        $body['returnPathDomain'] = $returnPathDomain;

        return new PostmarkSenderSignature($this->processRestRequest('POST', '/senders/', $body));
    }

    /**
     * Alter the defaults for a Sender Signature.
     *
     * @param integer $id The ID for the Sender Signature we wish to modify.
     * @param string|null $name The name of the Sender Signature.
     * @param string|null $replyToEmail The reply-to email address for the Sender Signature.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     * @return PostmarkSenderSignature
     */
    function editSenderSignature(int $id, string $name = NULL, string $replyToEmail = NULL, string $returnPathDomain = NULL): PostmarkSenderSignature
    {
        $body = array();
        $body['name'] = $name;
        $body['replyToEmail'] = $replyToEmail;
        $body['returnPathDomain'] = $returnPathDomain;

        return new PostmarkSenderSignature($this->processRestRequest('PUT', "/senders/$id", $body));
    }

    /**
     * Delete a Sender Signature with the given ID.
     *
     * @param integer $id The ID for the Sender Signature we wish to delete.
     * @return PostmarkResponse
     */
    function deleteSenderSignature(int $id): PostmarkResponse
    {
        return new PostmarkResponse($this->processRestRequest('DELETE', "/senders/$id"));
    }

    /**
     * Cause a new verification email to be sent for an existing (unverified) Sender Signature.
     * Sender Signatures require verification before they may be used to send email through the Postmark API.
     *
     * @param integer $id The ID for the Sender Signature to which we wish to resend a verification email.
     * @return PostmarkResponse
     */
    function resendSenderSignatureConfirmation(int $id): PostmarkResponse
    {
        return new PostmarkResponse($this->processRestRequest('POST', "/senders/$id/resend"));
    }

    /**
     * Get a "page" of Domains.
     *
     * @param integer $count The number of Domains to retrieve with this request.
     *  param  integer $offset The number of Domains to 'skip' when 'paging' through them.
     * @param int $offset
     * @return PostmarkDomainList
     */
    function listDomains($count = 100, $offset = 0): PostmarkDomainList
    {

        $query = array();
        $query['count'] = $count;
        $query['offset'] = $offset;

        $response = $this->processRestRequest('GET', '/domains/', $query);

        return new PostmarkDomainList($response);
    }

    /**
     * Get information for a specific Domain.
     *
     * @param integer $id The ID for the Domains you wish to retrieve.
     * @return PostmarkDomainDetails
     */
    function getDomain(int $id): PostmarkDomainDetails
    {
        $tempDomain = $this->processRestRequest('GET', "/domains/$id");
        return new PostmarkDomainDetails($tempDomain);
    }

    /**
     * Create a new Domain with the given Name.
     *
     * @param string $name The name of the Domain.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     * @return PostmarkDomainDetails
     */
    function createDomain(string $name, string $returnPathDomain = NULL): PostmarkDomainDetails
    {
        $body = array();
        $body['name'] = $name;
        $body['returnPathDomain'] = $returnPathDomain;

        $tempDomain = $this->processRestRequest('POST', '/domains/', $body);
        return new PostmarkDomainDetails($tempDomain);
    }

    /**
     * Alter the properties of a Domain.
     *
     * @param integer $id The ID for the Domain we wish to modify.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Domain.
     * @return PostmarkDomainDetails
     */
    function editDomain(int $id, string $returnPathDomain = NULL): PostmarkDomainDetails
    {
        $body = array();
        $body['returnPathDomain'] = $returnPathDomain;

        $tempDomain = $this->processRestRequest('PUT', "/domains/$id", $body);
        return new PostmarkDomainDetails($tempDomain);
    }

    /**
     * Delete a Domain with the given ID.
     *
     * @param integer $id The ID for the Domain we wish to delete.
     * @return PostmarkResponse
     */
    function deleteDomain(int $id): PostmarkResponse
    {
        return new PostmarkResponse($this->processRestRequest('DELETE', "/domains/$id"));
    }

    /**
     * Request that the Postmark API verify DKIM keys associated
     * with the Domain.
     *
     * @param integer $id The ID of the Domain we wish to verify DKIM keys on.
     * @return PostmarkDomainDetails
     */
    function verifyDKIM(int $id): PostmarkDomainDetails
    {
        $tempDomain = $this->processRestRequest('PUT', "/domains/$id/verifyDkim");
        return new PostmarkDomainDetails($tempDomain);
    }

    /**
     * Request that the Postmark API verify Return-Path DNS records associated
     * with the Domain.
     *
     * @param integer $id The ID of the Domain we wish to verify Return-Path DNS record on.
     * @return PostmarkDomainDetails
     */
    function verifyReturnPath(int $id): PostmarkDomainDetails
    {
        $tempDomain = $this->processRestRequest('PUT', "/domains/$id/verifyReturnPath");
        return new PostmarkDomainDetails($tempDomain);
    }

    /**
     * Rotate DKIM keys associated with the Domain. This key must be added
     * to your DNS records. Including DKIM is not required, but is recommended. For more information
     * on DKIM and its purpose, see http://www.dkim.org/
     *
     * @param integer $id The ID for the Domain for which we wish to get an updated DKIM configuration.
     * @return PostmarkDomain
     */
    function rotateDKIMForDomain(int $id): PostmarkDomain
    {
        return new PostmarkDomain($this->processRestRequest('POST', "/domains/$id/rotatedkim"));
    }

    /**
     * This endpoint allows you to erase recipient data from a specific account - for example when you’re processing
     * Data Subject Requests (DSR) under GDPR or CCPA
     *
     * @param string $requestedBy The email address of the user that is making the request
     * @param string $requestedFor The email address of the recipient who's asking for their data to be removed.
     * @param bool $notifyWhenCompleted Specifies whether the RequestedBy email address is notified when the data
     * removal request is complete
     * @return DataRemovalRequestResponse
     */
    public function createDataRemovalRequest(
        string $requestedBy,
        string $requestedFor,
        bool   $notifyWhenCompleted
    ): DataRemovalRequestResponse
    {
        $body = [];
        $body['RequestedBy'] = $requestedBy;
        $body['RequestedFor'] = $requestedFor;
        $body['NotifyWhenCompleted'] = $notifyWhenCompleted;

        return new DataRemovalRequestResponse($this->processRestRequest('POST', '/data-removals', $body));
    }
//
//    /**
//	 * Review the status of your data removal requests
//	 *
//	 * @param int $id ID of data removal request
//	 */
//	public function getDataRemoval(int $id): DynamicResponseModel
//	{
//		return new DynamicResponseModel(
//			$this->processRestRequest('GET', sprintf('/data-removals/%s', $id))
//        );
//	}

}
