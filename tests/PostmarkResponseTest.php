<?php

namespace Postmark\Tests;

use PHPUnit\Framework\TestCase;
use Postmark\Models\PostmarkException;
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

    public function testItThrowsAnUnauthorizedException()
    {
        try {
            $response = $this->stubResponse(401, []);
            (new PostmarkResponse($response))->toArray();
            $this->fail('Exception was not thrown');
        } catch (PostmarkException $exception) {
            $this->assertSame(401, $exception->httpStatusCode);
            $this->assertContains('unauthorized', $exception->getMessage(), '', true);
        }
    }

    public function testItThrowsAnInternalServerErrorException()
    {
        try {
            $response = $this->stubResponse(500, []);
            (new PostmarkResponse($response))->toArray();
            $this->fail('Exception was not thrown');
        } catch (PostmarkException $exception) {
            $this->assertSame(500, $exception->httpStatusCode);
            $this->assertContains('internal server error', $exception->getMessage(), '', true);
        }
    }

    protected function stubResponse($statusCode = 200, $body = ['success' => true])
    {
        return new class($statusCode, $body) {
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
