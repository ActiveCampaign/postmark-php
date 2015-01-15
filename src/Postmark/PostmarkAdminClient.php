<?php

namespace Postmark;

use Postmark\Models\DynamicResponseModel as DynamicResponseModel;
use Postmark\PostmarkClientBase as PostmarkClientBase;

/**
 * The PostmarkAdminClient allows users to access and modify
 *  "Account-wide" settings. At this time the API supports
 *  management of the "Sender Signatures", and "Servers."
 */
class PostmarkAdminClient extends PostmarkClientBase {

	/**
	 * Create a new PostmarkAdminClient.
	 *
	 * @param string $account_token The Account Token used to access the Admin API.
	 * This token is NOT the same as a Server Token. You can get your account token
	 * from this page: https://postmarkapp.com/account/edit
	 */
	function __construct($account_token) {
		parent::__construct($account_token, "X-Postmark-Account-Token");
	}

	/**
	 * Request a given server by ID.
	 *
	 * @param int $id  The Id for the server you wish to retrieve.
	 * @return DynamicResponseModel
	 */
	function getServer($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/servers/$id"));
	}

	/**
	 * Get a list of all servers configured on the account.
	 *
	 * @param integer $count  The number of servers to retrieve in the request, defaults to 100.
	 * @param integer $offset  The number of servers to "skip" when paging through lists of servers.
	 * @param string $name
	 * @return DynamicResponseModel
	 */
	function listServers($count = 100, $offset = 0, $name = NULL) {
		$query = array();
		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/servers/', $query));
	}

	/**
	 * Delete a Server used for sending/receiving mail. NOTE: To protect your account, you'll need to
	 * contact support and request that they enable this feature on your account before you can use this
	 * client to delete Servers.
	 *
	 * @param  integer $id  The ID of the Server to delete.
	 * :return DynamicResponseModel
	 */
	function deleteServer($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/servers/$id"));
	}

	/**
	 * Modify an existing Server. Any parameters passed with NULL will be
	 * ignored (their existing values will not be modified).
	 *
	 * @param  integer $id  The Identity of the Server to modify.
	 * @param  string $name
	 * @param  string $color
	 * @param  bool $rawEmailEnabled
	 * @param  bool $smtpApiActivated
	 * @param  string $inboundHookUrl
	 * @param  string $bounceHookUrl
	 * @param  string $openHookUrl
	 * @param  bool $postFirstOpenOnly
	 * @param  bool $trackOpens
	 * @param  string $inboundDomain
	 * @param  integer $inboundSpamThreshold
	 * @return DynamicResponseModel
	 */
	function editServer($id, $name = NULL, $color = NULL,
		$rawEmailEnabled = NULL, $smtpApiActivated = NULL, $inboundHookUrl = NULL,
		$bounceHookUrl = NULL, $openHookUrl = NULL, $postFirstOpenOnly = NULL,
		$trackOpens = NULL, $inboundDomain = NULL, $inboundSpamThreshold = NULL) {

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

		$response = new DynamicResponseModel($this->processRestRequest('PUT', "/servers/$id", $body));
		$response["ID"] = $id;
		return $response;
	}

	/**
	 * Create a new Server. Any parameters passed with NULL will be
	 * ignored (their default values will be used).
	 *
	 * @param  string $name
	 * @param  string $color  The color shown for the server in the Postmark web UI (may be any of the following: purple, blue, turqoise, green, red, yellow, grey)
	 * @param  bool $rawEmailEnabled
	 * @param  bool $smtpApiActivated
	 * @param  string $inboundHookUrl
	 * @param  string $bounceHookUrl
	 * @param  string $openHookUrl
	 * @param  bool $postFirstOpenOnly
	 * @param  bool $trackOpens
	 * @param  string $inboundDomain
	 * @param  integer $inboundSpamThreshold Between 0 and 30.
	 *  rtype: DynamicResponseModel
	 */
	function createServer($name, $color = NULL,
		$rawEmailEnabled = NULL, $smtpApiActivated = NULL, $inboundHookUrl = NULL,
		$bounceHookUrl = NULL, $openHookUrl = NULL, $postFirstOpenOnly = NULL,
		$trackOpens = NULL, $inboundDomain = NULL, $inboundSpamThreshold = NULL) {

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

		return new DynamicResponseModel($this->processRestRequest('POST', '/servers/', $body));
	}

	/**
	 * Get a "page" of Sender Signatures.
	 *
	 * @param  integer $count
	 *  param  integer $offset
	 * @return DynamicResponseModel
	 */
	function listSenderSignatures($count = 100, $offset = 0) {

		$query = array();
		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/senders/', $query));
	}

	/**
	 * Get information for a sepcific Sender Signature.
	 *
	 * @param  integer $id  The ID for the Sender Signature you wish to retrieve.
	 * @return DynamicResponseModel
	 */
	function getSenderSignature($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/senders/$id"));
	}

	/**
	 * Create a new Sender Signature for a given email address. Note that you will need to
	 * "verify" this Sender Signature by following a link that will be emailed to the "fromEmail"
	 * address specified when calling this method.
	 *
	 * @param  string $fromEmail
	 * @param  string $name
	 * @param  string $replyToEmail
	 * @return DynamicResponseModel
	 */
	function createSenderSignature($fromEmail, $name, $replyToEmail = NULL) {

		$body = array();
		$body['fromEmail'] = $fromEmail;
		$body['name'] = $name;
		$body['replyToEmail'] = $replyToEmail;

		return new DynamicResponseModel($this->processRestRequest('POST', '/senders/', $body));
	}

	/**
	 * Alter the defaults for a Sender Signature.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @param  string $replyToEmail
	 * @return DynamicResponseModel
	 */
	function editSenderSignature($id, $name = NULL, $replyToEmail = NULL) {

		$body = array();
		$body['name'] = $name;
		$body['replyToEmail'] = $replyToEmail;

		return new DynamicResponseModel($this->processRestRequest('PUT', "/senders/$id", $body));
	}

	/**
	 * Delete a Sender Signature with the given ID.
	 *
	 * @param  integer $id
	 * @return DynamicResponseModel
	 */
	function deleteSenderSignature($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/senders/$id"));
	}

	/**
	 * Cause a new verification email to be sent for an existing (unverified) Sender Signature.
	 * Sender Signatures require verification before they may be used to send email through the Postmark API.
	 *
	 * @param  integer $id
	 * @return DynamicResponseModel
	 */
	function resendSenderSignatureConfirmation($id) {
		return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/resend"));
	}

	/**
	 * Request that the Postmark API updates verify the SPF records associated
	 * with the Sender Signature's email address's domain. Configuring SPF is not required to use
	 * Postmark, but it is highly recommended, and can improve delivery rates.
	 *
	 * @param  integer $id
	 * @return DynamicResponseModel
	 */
	function verifySenderSignatureSPF($id) {
		return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/verifyspf"));
	}

	/**
	 * Cause a new DKIM key to be generated and associated with the Sender Signature. This key must be added
	 * to your email domain's DNS records. Including DKIM is not required, but is recommended. For more information
	 * on DKIM and its purpose, see http://www.dkim.org/
	 *
	 * @param  integer $id
	 * @return DynamicResponseModel
	 */
	function requestNewSenderSignatureDKIM($id) {
		return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/requestnewdkim"));
	}
}
?>