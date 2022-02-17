<?php

declare(strict_types=1);

namespace Postmark;

use Postmark\ClientBehaviour\PostmarkClientBase;
use Postmark\Models\DynamicResponseModel;

use function sprintf;

/**
 * The PostmarkAdminClient allows users to access and modify "Account-wide" settings.
 *
 * At this time the API supports management of the "Sender Signatures", "Domains", and "Servers".
 * The constructor requires an Account Token. This token is NOT the same as a Server Token.
 * You can get your account token from this page: https://postmarkapp.com/account/edit
 *
 * @link https://postmarkapp.com/account/edit
 */
final class PostmarkAdminClient extends PostmarkClientBase
{
    private const AUTH_HEADER_NAME = 'X-Postmark-Account-Token';

    protected function authorizationHeaderName(): string
    {
        return self::AUTH_HEADER_NAME;
    }

    /**
     * Request a given server by ID.
     *
     * @param int $id The Id for the server you wish to retrieve.
     */
    public function getServer(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/servers/%s', $id))
        );
    }

    /**
     * Get a list of all servers configured on the account.
     *
     * @param int         $count  The number of servers to retrieve in the request, defaults to 100.
     * @param int         $offset The number of servers to "skip" when paging through lists of servers.
     * @param string|null $name   Filter by server name.
     */
    public function listServers(int $count = 100, int $offset = 0, ?string $name = null): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/servers/', [
                'count' => $count,
                'offset' => $offset,
                'name' => $name,
            ])
        );
    }

    /**
     * Delete a Server used for sending/receiving mail. NOTE: To protect your account, you'll need to
     * contact support and request that they enable this feature on your account before you can use this
     * client to delete Servers.
     *
     * @param int $id The ID of the Server to delete.
     */
    public function deleteServer(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/servers/%s', $id))
        );
    }

    /**
     * Modify an existing Server. Any parameters passed with NULL will be
     * ignored (their existing values will not be modified).
     *
     * @param int         $id                      The ID of the Server we wish to modify.
     * @param string|null $name                    Set the name of the server.
     * @param string|null $color                   Set the color for the server in the Postmark WebUI (must be:
     *                                             'purple', 'blue', 'turqoise', 'green', 'red', 'yellow', or 'grey')
     * @param bool|null   $rawEmailEnabled         Enable raw email to be sent with inbound.
     * @param bool|null   $smtpApiActivated        Specifies whether SMTP is enabled on this server.
     * @param string|null $inboundHookUrl          URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl           URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl             URL to POST to everytime an open event occurs.
     * @param bool|null   $postFirstOpenOnly       If set to true, only the first open by a particular recipient will
     *                                             initiate the open webhook. Any subsequent opens of the same email
     *                                             by the same recipient will not initiate the webhook.
     * @param bool|null   $trackOpens              Indicates if all emails being sent through this server have
     *                                             open tracking enabled.
     * @param string|null $inboundDomain           Inbound domain for MX setup.
     * @param int|null    $inboundSpamThreshold    The maximum spam score for an inbound message before
     *                                             it's blocked (range from 0-30).
     * @param string|null $trackLinks              Indicates if all emails being sent through this server have
     *                                             link tracking enabled.
     * @param string|null $clickHookUrl            URL to POST to everytime a click event occurs.
     * @param string|null $deliveryHookUrl         URL to POST to everytime a click event occurs.
     * @param string|null $enableSmtpApiErrorHooks Specifies whether SMTP API Errors will be included
     *                                             with bounce webhooks.
     */
    public function editServer(
        int $id,
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
        ?string $deliveryHookUrl = null,
        ?string $enableSmtpApiErrorHooks = null
    ): DynamicResponseModel {
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

        $response = new DynamicResponseModel($this->processRestRequest('PUT', '/servers/' . $id, $body));
        $response['ID'] = $id;

        return $response;
    }

    /**
     * Create a new Server. Any parameters passed with NULL will be
     * ignored (their default values will be used).
     *
     * @param string      $name                    Set the name of the server.
     * @param string|null $color                   Set the color for the server in the Postmark WebUI
     *                                             (must be: 'purple', 'blue', 'turqoise', 'green', 'red', 'yellow',
     *                                              or 'grey')
     * @param bool|null   $rawEmailEnabled         Enable raw email to be sent with inbound.
     * @param bool|null   $smtpApiActivated        Specifies whether or not SMTP is enabled on this server.
     * @param string|null $inboundHookUrl          URL to POST to everytime an inbound event occurs.
     * @param string|null $bounceHookUrl           URL to POST to everytime a bounce event occurs.
     * @param string|null $openHookUrl             URL to POST to everytime an open event occurs.
     * @param bool|null   $postFirstOpenOnly       If set to true, only the first open by a particular recipient will
     *                                             initiate the open webhook. Any subsequent opens of the same email
     *                                             by the same recipient will not initiate the webhook.
     * @param bool|null   $trackOpens              Indicates if all emails being sent through this server have
     *                                             open tracking enabled.
     * @param string|null $inboundDomain           Inbound domain for MX setup.
     * @param int|null    $inboundSpamThreshold    The maximum spam score for an inbound message before
     *                                             it's blocked (range from 0-30).
     * @param string|null $trackLinks              Indicates if all emails being sent through this server
     *                                             have link tracking enabled.
     * @param string|null $clickHookUrl            URL to POST to everytime an click event occurs.
     * @param string|null $deliveryHookUrl         URL to POST to everytime an click event occurs.
     * @param string|null $enableSmtpApiErrorHooks Specifies whether or not SMTP API Errors will be included
     *                                             with bounce webhooks.
     */
    public function createServer(
        string $name,
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
        ?string $deliveryHookUrl = null,
        ?string $enableSmtpApiErrorHooks = null
    ): DynamicResponseModel {
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

        return new DynamicResponseModel($this->processRestRequest('POST', '/servers/', $body));
    }

    /**
     * Get a "page" of Sender Signatures.
     *
     * @param int $count  The number of Sender Signatures to retrieve with this request.
     * @param int $offset The number of Sender Signatures to 'skip' when 'paging' through them.
     */
    public function listSenderSignatures(int $count = 100, int $offset = 0): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/senders/', [
                'count' => $count,
                'offset' => $offset,
            ])
        );
    }

    /**
     * Get information for a specific Sender Signature.
     *
     * @param int $id The ID for the Sender Signature you wish to retrieve.
     */
    public function getSenderSignature(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/senders/%s', $id))
        );
    }

    /**
     * Create a new Sender Signature for a given email address. Note that you will need to
     * "verify" this Sender Signature by following a link that will be emailed to the "fromEmail"
     * address specified when calling this method.
     *
     * @param string      $fromEmail        The email address for the Sender Signature
     * @param string      $name             The name of the Sender Signature.
     * @param string|null $replyToEmail     The reply-to email address for the Sender Signature.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     */
    public function createSenderSignature(
        string $fromEmail,
        string $name,
        ?string $replyToEmail = null,
        ?string $returnPathDomain = null
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/senders/', [
                'fromEmail' => $fromEmail,
                'name' => $name,
                'replyToEmail' => $replyToEmail,
                'returnPathDomain' => $returnPathDomain,
            ])
        );
    }

    /**
     * Alter the defaults for a Sender Signature.
     *
     * @param int         $id               The ID for the Sender Signature we wish to modify.
     * @param string|null $name             The name of the Sender Signature.
     * @param string|null $replyToEmail     The reply-to email address for the Sender Signature.
     * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
     */
    public function editSenderSignature(
        int $id,
        ?string $name = null,
        ?string $replyToEmail = null,
        ?string $returnPathDomain = null
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/senders/%s', $id), [
                'name' => $name,
                'replyToEmail' => $replyToEmail,
                'returnPathDomain' => $returnPathDomain,
            ])
        );
    }

    /**
     * Delete a Sender Signature with the given ID.
     *
     * @param int $id The ID for the Sender Signature we wish to delete.
     */
    public function deleteSenderSignature(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/senders/%s', $id))
        );
    }

    /**
     * Cause a new verification email to be sent for an existing (unverified) Sender Signature.
     * Sender Signatures require verification before they may be used to send email through the Postmark API.
     *
     * @param int $id The ID for the Sender Signature to which we wish to resend a verification email.
     */
    public function resendSenderSignatureConfirmation(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/senders/%s/resend', $id))
        );
    }

    /**
     * Request that the Postmark API updates verify the SPF records associated
     * with the Sender Signature's email address's domain. Configuring SPF is not required to use
     * Postmark, but it is highly recommended, and can improve delivery rates.
     *
     * @deprecated verifyDomainSPF replaces this method
     *
     * @param int $id The ID for the Sender Signature for which we wish to verify the SPF records.
     */
    public function verifySenderSignatureSPF(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/senders/%s/verifyspf', $id))
        );
    }

    /**
     * Cause a new DKIM key to be generated and associated with the Sender Signature. This key must be added
     * to your email domain's DNS records. Including DKIM is not required, but is recommended. For more information
     * on DKIM and its purpose, see http://www.dkim.org/
     *
     * @deprecated rotateDKIMForDomain replaces this method.
     *
     * @param int $id The ID for the Sender Signature for which we wish to get an updated DKIM configuration.
     */
    public function requestNewSenderSignatureDKIM(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/senders/%s/requestnewdkim', $id))
        );
    }

    /**
     * Get a "page" of Domains.
     *
     * @param int $count  The number of Domains to retrieve with this request.
     * @param int $offset The number of Domains to 'skip' when 'paging' through them.
     */
    public function listDomains(int $count = 100, int $offset = 0): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/domains/', [
                'count' => $count,
                'offset' => $offset,
            ])
        );
    }

   /**
    * Get information for a specific Domain.
    *
    * @param int $id The ID for the Domains you wish to retrieve.
    */
    public function getDomain(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/domains/%s', $id))
        );
    }

   /**
    * Create a new Domain with the given Name.
    *
    * @param string      $name             The name of the Domain.
    * @param string|null $returnPathDomain The custom Return-Path domain for the Sender Signature.
    */
    public function createDomain(string $name, ?string $returnPathDomain = null): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/domains/', [
                'returnPathDomain' => $returnPathDomain,
                'name' => $name,
            ])
        );
    }

   /**
    * Alter the properties of a Domain.
    *
    * @param int         $id               The ID for the Domain we wish to modify.
    * @param string|null $returnPathDomain The custom Return-Path domain for the Domain.
    */
    public function editDomain(int $id, ?string $returnPathDomain = null): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/domains/%s', $id), ['returnPathDomain' => $returnPathDomain])
        );
    }

   /**
    * Delete a Domain with the given ID.
    *
    * @param int $id The ID for the Domain we wish to delete.
    */
    public function deleteDomain(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/domains/%s', $id))
        );
    }

   /**
    * Request that the Postmark API verify the SPF records associated
    * with the Domain. Configuring SPF is not required to use
    * Postmark, but it is highly recommended, and can improve delivery rates.
    *
    * @param int $id The ID for the Domain for which we wish to verify the SPF records.
    */
    public function verifyDomainSPF(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/domains/%s/verifyspf', $id))
        );
    }

   /**
    * Rotate DKIM keys associated with the Domain. This key must be added
    * to your DNS records. Including DKIM is not required, but is recommended. For more information
    * on DKIM and its purpose, see http://www.dkim.org/
    *
    * @param int $id The ID for the Domain for which we wish to get an updated DKIM configuration.
    */
    public function rotateDKIMForDomain(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/domains/%s/rotatedkim', $id))
        );
    }
}
