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
        if ($this->response->getStatusCode() !== 200) $this->throwException();

        // Casting BIGINT as STRING instead of the default FLOAT, to avoid loss of precision.
        return json_decode($this->response->getBody(), true, 512, JSON_BIGINT_AS_STRING);
    }

    /**
     * @return mixed
     * @throws PostmarkException
     */
    protected function throwException()
    {
        switch ($this->response->getStatusCode()) {
            case 401:
                throw PostmarkException::unauthorized();
            case 500:
                throw PostmarkException::internalServerError();
            case 503:
                throw PostmarkException::unavailable();
            // This should cover case 422, and any others that are possible:
            default:
                throw PostmarkException::from($this->response);
        }
    }
}