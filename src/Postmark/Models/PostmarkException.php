<?php

/** @noinspection PhpMissingFieldTypeInspection */

namespace Postmark\Models;

use Exception;

/**
 * The exception thrown when the Postmark Client receives an error from the API.
 */
class PostmarkException extends Exception {
    var $message;
    var $httpStatusCode;
    var $postmarkApiErrorCode;

    /**
     * @return mixed
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * @param mixed $httpStatusCode
     * @return PostmarkException
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->httpStatusCode = $httpStatusCode;
        return $this;
    }

    public static function from($response): PostmarkException
    {
        $ex = new self();
        $body = json_decode($response->getBody(), true);
        $ex->httpStatusCode = $response->getStatusCode();
        $ex->postmarkApiErrorCode = $body['ErrorCode'] ?? 'invalid-response';
        $ex->message = $body['Message'] ?? 'This appears to be an invalid response. Please contact support.';

        return $ex;
    }

    public static function unavailable(): PostmarkException
    {
        $ex = new self();
        $ex->httpStatusCode = 503;
        $ex->message = 'The Postmark API is currently unavailable, please try your request later.';
        return $ex;
    }

    public static function unauthorized(): PostmarkException
    {
        $ex = new self();
        $ex->httpStatusCode = 401;
        $ex->message = 'Unauthorized: Missing or incorrect API token in header. ' .
            'Please verify that you used the correct token when you constructed your client.';
        return $ex;
    }

    public static function internalServerError(): PostmarkException
    {
        $ex = new self();
        $ex->httpStatusCode = 500;
        $ex->message = 'Internal Server Error: This is an issue with Postmark’s servers processing your request. ' .
            'In most cases the message is lost during the process, ' .
            'and Postmark is notified so that we can investigate the issue.';
        return $ex;
    }
}