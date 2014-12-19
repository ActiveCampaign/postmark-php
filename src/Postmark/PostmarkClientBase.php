<?php
namespace Postmark;

abstract class PostmarkClientBase {

	public static $BASE_URL = "https://api.postmarkapp.com";

	protected $authorization_token = NULL;
	protected $authorization_header = NULL;

	protected function __construct($token, $header) {
		$this->authorization_header = $header;
		$this->authorization_token = $token;
	}

	protected function processRestRequest($method, $path, $body, $headers) {

		$client = new \Guzzle\Http\Client();

		$headers = $headers ?: [];

		$url = PostmarkClientBase::$BASE_URL . $path;

		$request = $client->createRequest($method, $url,
			[
				'headers' => $headers,
			]);

		$request->setHeader('Accept', 'application/json');
		$request->setHeader('Content-Type', 'application/json');
		$request->setHeader($this->authorization_header, $this->authorization_token);

		if ($body != NULL) {
			$json_body = json_encode($body);
			$request->setBody($json_body);
		}

		$response = $client->send($request);
		$result = json_decode($response->getBody());

		return $result;
	}
}

?>