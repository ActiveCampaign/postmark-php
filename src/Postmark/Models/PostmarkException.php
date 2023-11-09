<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace Postmark\Models;

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class PostmarkException extends \Exception {
	public $message;
	public $httpStatusCode;
	public $postmarkApiErrorCode;
}