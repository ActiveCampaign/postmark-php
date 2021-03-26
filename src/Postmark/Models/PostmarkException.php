<?php

namespace Postmark\Models;

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class PostmarkException extends \Exception {
	var $message;
	var $httpStatusCode;
	var $postmarkApiErrorCode;

    public static function unauthorized()
    {
        $ex = new self();
        $ex->message = 'Unauthorized: Missing or incorrect API token in header. ' .
            'Please verify that you used the correct token when you constructed your client.';
        $ex->httpStatusCode = 401;
        return $ex;
	}
}
