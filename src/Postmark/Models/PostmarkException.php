<?php

namespace Postmark\Models;

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class PostmarkException extends \Exception {
	var $message;
	var $httpStatusCode;
	var $postmarkApiErrorCode;

    public static function unavailable()
    {
        $ex = new self();
        $ex->httpStatusCode = 503;
        $ex->message = 'The Postmark API is currently unavailable, please try your request later.';
        return $ex;
	}

    public static function unauthorized()
    {
        $ex = new self();
        $ex->message = 'Unauthorized: Missing or incorrect API token in header. ' .
            'Please verify that you used the correct token when you constructed your client.';
        $ex->httpStatusCode = 401;
        return $ex;
	}

    public static function internalServerError()
    {
        $ex = new self();
        $ex->httpStatusCode = 500;
        $ex->message = 'Internal Server Error: This is an issue with Postmark’s servers processing your request. ' .
            'In most cases the message is lost during the process, ' .
            'and Postmark is notified so that we can investigate the issue.';
        return $ex;
	}
}
