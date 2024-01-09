<?php

namespace Postmark\Models;

use GuzzleHttp\Psr7\Response;

class PostmarkHttpResponse
{
    private Response $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @throws PostmarkException
     */
    public function toArray(): mixed
    {
        if (200 !== $this->response->getStatusCode()) {
            $this->throwException();
        }

        // Casting BIGINT as STRING instead of the default FLOAT, to avoid loss of precision.
        return json_decode((string) $this->response->getBody(), true, 512, JSON_BIGINT_AS_STRING);
    }

    /**
     * @throws PostmarkException
     */
    protected function throwException(): mixed
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
