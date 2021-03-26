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
        $statusCode = 401;
        $expectedMessage = 'unauthorized';

        $this->assertException($statusCode, $expectedMessage);
    }

    public function testItThrowsAnInternalServerErrorException()
    {
        $statusCode = 500;
        $expectedMessage = 'internal server error';

        $this->assertException($statusCode, $expectedMessage);
    }

    public function testItThrowsAnUnavailableException()
    {
        $statusCode = 503;
        $expectedMessage = 'unavailable';

        $this->assertException($statusCode, $expectedMessage);
    }

    public function testItThrowsA422Exception()
    {
        $statusCode = 422;
        $expectedMessage = 'unavailable';
        $postmarkApiErrorCode = 'postmark api error code';

        $this->assertException($statusCode, $expectedMessage, ['ErrorCode' => $postmarkApiErrorCode, 'Message' => $expectedMessage], $postmarkApiErrorCode);
    }

    public function testItThrowsAnExceptionIfThereAreNoBodyKeys()
    {
        $statusCode = 404;
        $expectedMessage = 'This appears to be an invalid response. Please contact support.';
        $postmarkApiErrorCode = 'invalid-response';

        $this->assertException($statusCode, $expectedMessage, [], $postmarkApiErrorCode);
    }

    protected function assertException($statusCode, $expectedMessage, $responseBody = [], $expectedPostmarkApiErrorCode = null)
    {
        try {
            $response = $this->stubResponse($statusCode, $responseBody);
            (new PostmarkResponse($response))->toArray();
            $this->fail('Exception was not thrown');
        } catch (PostmarkException $exception) {
            $this->assertSame($statusCode, $exception->httpStatusCode);
            $this->assertContains($expectedMessage, $exception->getMessage(), '', true);
            if ($expectedPostmarkApiErrorCode) $this->assertSame($expectedPostmarkApiErrorCode, $exception->postmarkApiErrorCode);
        }
    }

    private function stubResponse($statusCode = 200, $body = ['success' => true])
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
