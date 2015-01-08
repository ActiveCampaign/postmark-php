<?php

namespace Postmark;

use Postmark\Models\DynamicResponseModel as DynamicResponseModel;
use Postmark\PostmarkClientBase as PostmarkClientBase;

/**
 * The PostmarkAdminClient allows users to access and modify
 * "account-wide" settings. At this time the API supports
 * create, read, update, and delete operations are supported
 * for the "Sender Signatures", and "Servers."
 */
class PostmarkAdminClient extends PostmarkClientBase {

	/**
	 * Create a new PostmarkAdminClient.
	 * @param string $account_token is NOT the same as a Server Token -
	 * you can get your account token from this page: https://postmarkapp.com/account/edit
	 */
	function __construct($account_token) {
		parent::__construct($account_token, "X-Postmark-Account-Token");
	}

	/**
	 * Request a given server by ID.
	 * @param  int
	 * @return DynamicResponseModel
	 */
	function getServer($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/servers/$id"));
	}

	/**
	 * Get a list of all servers configured on the account.
	 * @param  integer
	 * @param  integer
	 * @param  string
	 * @return DynamicResponseModel
	 */
	function listServers($count = 100, $offset = 0, $name = NULL) {
		$query = [];
		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/servers/', $query));
	}

	/**
	 * Delete a Server used for sending/receiving mail. NOTE: To protect your account, you'll need to
	 * contact support and request that they enable this feature on your account before you can use this
	 * client to delete Servers.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function deleteServer($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/servers/$id"));
	}

	/**
	 * Modify an existing Server. Any parameters passed with NULL will be
	 * ignored (their existing values will not be modified).
	 * @param  integer $id is the Identity of the Server to modify.
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
	function editServer($id, $name = NULL, $color = NULL,
		$rawEmailEnabled = NULL, $smtpApiActivated = NULL, $inboundHookUrl = NULL,
		$bounceHookUrl = NULL, $openHookUrl = NULL, $postFirstOpenOnly = NULL,
		$trackOpens = NULL, $inboundDomain = NULL, $inboundSpamThreshold = NULL) {

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

		$response = new DynamicResponseModel($this->processRestRequest('PUT', "/servers/$id", $body));
		$response["ID"] = $id;
		return $response;
	}

	/**
	 * Create a new Server. Any parameters passed with NULL will be
	 * ignored (their default values will be used).
	 * @param  string
	 * @param  string $color (maybe any of the following: purple, blue, turqoise, green, red, yellow, grey)
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
	function createServer($name, $color = NULL,
		$rawEmailEnabled = NULL, $smtpApiActivated = NULL, $inboundHookUrl = NULL,
		$bounceHookUrl = NULL, $openHookUrl = NULL, $postFirstOpenOnly = NULL,
		$trackOpens = NULL, $inboundDomain = NULL, $inboundSpamThreshold = NULL) {

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

		return new DynamicResponseModel($this->processRestRequest('POST', '/servers/', $body));
	}

	/**
	 * Get a "page" of Sender Signatures.
	 * @param  integer
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function listSenderSignatures($count = 100, $offset = 0) {

		$query = [];
		$query['count'] = $count;
		$query['offset'] = $offset;

		return new DynamicResponseModel($this->processRestRequest('GET', '/senders/', $query));
	}

	/**
	 * Get information for a sepcific Sender Signature.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function getSenderSignature($id) {
		return new DynamicResponseModel($this->processRestRequest('GET', "/senders/$id"));
	}

	/**
	 * Create a new Sender Signature for a given email address. Note that you will need to
	 * "verify" this Sender Signature by following a link that will be emailed to the "fromEmail"
	 * address specified when calling this method.
	 * @param  string
	 * @param  string
	 * @param  string
	 * @return DynamicResponseModel
	 */
	function createSenderSignature($fromEmail, $name, $replyToEmail = NULL) {

		$body = [];
		$body['fromEmail'] = $fromEmail;
		$body['name'] = $name;
		$body['replyToEmail'] = $replyToEmail;

		return new DynamicResponseModel($this->processRestRequest('POST', '/senders/', $body));
	}

	/**
	 * Alter the defaults for a Sender Signature.
	 * @param  integer
	 * @param  string
	 * @param  string
	 * @return DynamicResponseModel
	 */
	function editSenderSignature($id, $name = NULL, $replyToEmail = NULL) {

		$body = [];
		$body['name'] = $name;
		$body['replyToEmail'] = $replyToEmail;

		return new DynamicResponseModel($this->processRestRequest('PUT', "/senders/$id", $body));
	}

	/**
	 * Delete a Sender Signature with the given ID.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function deleteSenderSignature($id) {
		return new DynamicResponseModel($this->processRestRequest('DELETE', "/senders/$id"));
	}

	/**
	 * Cause a new verification email to be sent for an existing (unverified) Sender Signature.
	 * Sender Signatures require verification before they may be used to send email through the Postmark API.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function resendSenderSignatureConfirmation($id) {
		return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/resend"));
	}

	/**
	 * Request that the Postmark API updates verify the SPF records associated
	 * with the Sender Signature's email address's domain. Configuring SPF is not required to use
	 * Postmark, but it is highly recommended, and can improve delivery rates.
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function verifySenderSignatureSPF($id) {
		return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/verifyspf"));
	}

	/**
	 * Cause a new DKIM key to be generated and associated with the Sender Signature. This key must be added
	 * to your email domain's DNS records. Including DKIM is not required, but is recommended. For more information
	 * on DKIM and its purpose, see http://www.dkim.org/
	 * @param  integer
	 * @return DynamicResponseModel
	 */
	function requestNewSenderSignatureDKIM($id) {
		return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/requestnewdkim"));
	}
}
?>