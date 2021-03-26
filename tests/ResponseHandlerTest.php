<?php

namespace Postmark;

use PHPUnit\Framework\TestCase;

class ResponseHandlerTest extends TestCase
{
    public function testItReturnsTheResponseBody()
    {
        $response = new class($statusCode = 200, $body = ['success' => true]) {
            private $statusCode;
            private $body;

            public function __construct($statusCode, $body)
            {
                $this->statusCode = $statusCode;
                $this->body = $body;
            }

            public function getStatusCode()
            {
                return $this->statusCode;
            }

            public function getBody()
            {
                return json_encode($this->body);
            }
        };

        $instance = new ResponseHandler();
        $result = $instance->handle($response);
        $this->assertSame(['success' => true], $result);
    }
}
