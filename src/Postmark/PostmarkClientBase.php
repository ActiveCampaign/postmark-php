<?php

/*
 * Author:   Wildbit (http://wildbit.com)
 * License:  http://creativecommons.org/licenses/MIT/ MIT
 * Link:     https://github.com/wildbit/postmark-php/
 */

namespace Postmark;

use Postmark\Models\PostmarkException as PostmarkException;

/**
 * This is the core class that interacts with the Postmark API. All clients should
 * inherit fromt this class.
 */
abstract class PostmarkClientBase {

	/**
	 * BASE_URL is "https://api.postmarkapp.com"
	 *
	 * You may modify this value to disable SSL support, but it is not recommended.
	 *
	 * @var string
	 */
	public static $BASE_URL = "https://api.postmarkapp.com";

	protected $authorization_token = NULL;
	protected $authorization_header = NULL;
	protected $version = NULL;
	protected $os = NULL;
	protected $timeout = 30;

	protected function __construct($token, $header, $timeout = 30) {
		$this->authorization_header = $header;
		$this->authorization_token = $token;
		$this->version = phpversion();
		$this->os = PHP_OS;
		$this->timeout = $timeout;
	}

	/**
	 * The base request method for all API access.
	 *
	 * @param string $method The request VERB to use (GET, POST, PUT, DELETE)
	 * @param string $path The API path.
	 * @param array $body The content to be used (either as the query, or the json post/put body)
	 * @return object
	 */
	protected function processRestRequest($method = NULL, $path = NULL, $body = NULL) {
		$client = new \GuzzleHttp\Client([
			'exceptions' => false,
			'timeout' => $this->timeout,
        ]);

		$url = PostmarkClientBase::$BASE_URL . $path;

		$options = array();

		if ($body != NULL) {
			$cleanParams = array();

			foreach ($body as $key => $value) {
				if ($value !== NULL) {
					$cleanParams[$key] = $value;
				}
			}

			switch ($method) {
				case 'GET':
				case 'HEAD':
				case 'DELETE':
				case 'OPTIONS':
					$options['query'] = $cleanParams;
					break;
				case 'PUT':
				case 'POST':
				case 'PATCH':
					$options['json'] = $cleanParams;
					break;
			}
		}

		$v = $this->version;
		$o = $this->os;

		$options['headers'] = array('User-Agent' => "Postmark-PHP (PHP Version:$v, OS:$o)",
					 'Accept' => 'application/json',
					 'Content-Type' => 'application/json',
					 $this->authorization_header => $this->authorization_token);

		
		$response = $client->request($method, $url, $options);
		
		$result = NULL;

		switch ($response->getStatusCode()) {
			case 200:
				$result = json_decode($response->getBody(), true);
				break;
			case 401:

				$ex = new PostmarkException();
				$ex->message = 'Unauthorized: Missing or incorrect API token in header. ' .
				'Please verify that you used the correct token when you constructed your client.';
				$ex->httpStatusCode = 401;
				throw $ex;
				break;
			case 422:
				$ex = new PostmarkException();

				$body = json_decode($response->getBody(), true);

				$ex->httpStatusCode = 422;
				$ex->postmarkApiErrorCode = $body['ErrorCode'];
				$ex->message = $body['Message'];

				throw $ex;
				break;
			case 500:
				$ex = new PostmarkException();
				$ex->httpStatusCode = 500;
				$ex->message = 'Internal Server Error: This is an issue with Postmark’s servers processing your request. ' .
				'In most cases the message is lost during the process, ' .
				'and Postmark is notified so that we can investigate the issue.';
				throw $ex;
				break;
		}
		return $result;
	}
}

?>
