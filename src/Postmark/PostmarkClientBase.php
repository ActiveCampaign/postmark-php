<?php

/*
 * Author:   Wildbit (http://wildbit.com)
 * License:  http://creativecommons.org/licenses/MIT/ MIT
 * Link:     https://postmark-php.readthedocs.org/en/latest/
 */

namespace Postmark;

use Postmark\PostmarkClientBase as PostmarkClientBase;

/**
 * This is the core class that interacts with the Postmark API. All clients should
 * inherit fromt this class.
 */
abstract class PostmarkClientBase {

	/**
	 * BASE_URL is "https://api.postmarkapp.com" - you may modify this value
	 * to disable SSL support, but it is not recommended.
	 * @var string
	 */
	public static $BASE_URL = "https://api.postmarkapp.com";

	protected $authorization_token = NULL;
	protected $authorization_header = NULL;

	protected function __construct($token, $header) {
		$this->authorization_header = $header;
		$this->authorization_token = $token;
	}

	protected function processRestRequest($method = NULL, $path = NULL, $body = NULL) {

		$client = new \Guzzle\Http\Client();

		$url = PostmarkClientBase::$BASE_URL . $path;

		$request = $client->createRequest($method, $url, []);

		$request->setHeader('Accept', 'application/json');
		$request->setHeader('Content-Type', 'application/json');
		$request->setHeader($this->authorization_header, $this->authorization_token);

		if ($body != NULL) {
			switch ($method) {
				case 'GET':
				case 'HEAD':
				case 'DELETE':
				case 'OPTIONS':
					$query = $request->getQuery();
					foreach ($body as $key => $value) {
						if ($value !== NULL) {
							$query[$key] = $value;
						}
					}
					break;
				case 'PUT':
				case 'POST':
				case 'PATCH':
					$cleanBody = [];
					foreach ($body as $key => $value) {
						if ($value !== NULL) {
							$cleanBody[$key] = $value;
						}
					}
					$json_body = json_encode($cleanBody);
					$request->setBody($json_body);
					break;
			}
		}

		$response = $client->send($request);
		$result = $response->json();

		return $result;
	}
}

?>