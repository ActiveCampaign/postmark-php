<?php

namespace Postmark;

use Postmark\PostmarkClientBase as PostmarkClientBase;

class PostmarkAdminClient extends PostmarkClientBase {
	function __construct($account_token) {
		parent::__construct($account_token, "X-Postmark-Account-Token");
	}
}

function getServer($id) {
	return new DynamicResponseModel($this->processRestRequest('GET', "/servers/$id"));
}

function listServers($count = 100, $offset = 0, $name = NULL) {
	$query = [];
	$query['count'] = $count;
	$query['offset'] = $offset;

	return new DynamicResponseModel($this->processRestRequest('GET', '/servers/', $query));
}

function deleteServer($id) {
	return new DynamicResponseModel($this->processRestRequest('DELETE', "/servers/$id"));
}

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

	return new DynamicResponseModel($this->processRestRequest('PUT', "/servers/$id", $body));
}

function createServer($name = NULL, $color = NULL,
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

function listSenderSignatures($count = 100, $offset = 0) {

	$query = [];
	$query['count'] = $count;
	$query['offset'] = $offset;

	return new DynamicResponseModel($this->processRestRequest('GET', '/senders/', $query));
}

function getSenderSignature($id) {
	return new DynamicResponseModel($this->processRestRequest('GET', "/senders/$id"));
}

function createSenderSignature($fromEmail, $name, $replyToEmail = NULL) {

	$body = [];
	$body['fromEmail'] = $fromEmail;
	$body['name'] = $name;
	$body['replyToEmail'] = $replyToEmail;

	return new DynamicResponseModel($this->processRestRequest('POST', '/senders/', $body));
}

function editSenderSignature($id, $name = NULL, $replyToEmail = NULL) {

	$body = [];
	$body['name'] = $name;
	$body['replyToEmail'] = $replyToEmail;

	return new DynamicResponseModel($this->processRestRequest('PUT', "/senders/$id", $body));
}

function deleteSenderSignature($id) {
	return new DynamicResponseModel($this->processRestRequest('DELETE', "/senders/$id"));
}

function resendSenderSignatureConfirmation($id) {
	return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/resend"));
}

function verifySenderSignatureSPF($id) {
	return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/verifyspf"));
}

function requestNewSenderSignatureDKIM($id) {
	return new DynamicResponseModel($this->processRestRequest('POST', "/senders/$id/requestnewdkim"));
}

?>