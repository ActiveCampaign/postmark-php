<?php

namespace Postmark\Tests;

use PHPUnit\Framework\TestCase;
use Postmark\PostmarkResponse;

class PostmarkResponseTest extends TestCase
{
    public function testItReturnsTheResponseBody()
    {
        $response = $this->stubResponse();
        $instance = new PostmarkResponse($response);
        $result = $instance->toArray();
        $this->assertSame(['success' => true], $result);
    }

    protected function stubResponse()
    {
        return new class($statusCode = 200, $body = ['success' => true]) {
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
    }
}
