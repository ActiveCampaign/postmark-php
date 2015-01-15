<?php

namespace Postmark\Models;

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class PostmarkException extends \Exception {
	var $message;
	var $httpStatusCode;
	var $postmarkApiErrorCode;
}

?>