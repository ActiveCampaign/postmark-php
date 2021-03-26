<?php

namespace Postmark;

use Postmark\Models\PostmarkException;

class PostmarkResponse
{
    private $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     * @throws PostmarkException
     */
    public function toArray()
    {
        switch ($this->response->getStatusCode()) {
            case 200:
                // Casting BIGINT as STRING instead of the default FLOAT, to avoid loss of precision.
                return json_decode($this->response->getBody(), true, 512, JSON_BIGINT_AS_STRING);
            case 401:
                throw PostmarkException::unauthorized();
            case 500:
                $ex = new PostmarkException();
                $ex->httpStatusCode = 500;
                $ex->message = 'Internal Server Error: This is an issue with Postmark’s servers processing your request. ' .
                    'In most cases the message is lost during the process, ' .
                    'and Postmark is notified so that we can investigate the issue.';
                throw $ex;
            case 503:
                $ex = new PostmarkException();
                $ex->httpStatusCode = 503;
                $ex->message = 'The Postmark API is currently unavailable, please try your request later.';
                throw $ex;
            // This should cover case 422, and any others that are possible:
            default:
                $ex = new PostmarkException();
                $body = json_decode($this->response->getBody(), true);
                $ex->httpStatusCode = $this->response->getStatusCode();
                $ex->postmarkApiErrorCode = $body['ErrorCode'];
                $ex->message = $body['Message'];
                throw $ex;
        }
    }
}