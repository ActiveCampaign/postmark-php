<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Exception;

use Laminas\Diactoros\Request;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\StreamFactory;
use PHPUnit\Framework\TestCase;
use Postmark\Exception\RequestFailure;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class RequestFailureTest extends TestCase
{
    private Request $request;
    private Response $response;
    private StreamFactory $streamFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new Request();
        $this->response = new Response();
        $this->streamFactory = new StreamFactory();
    }

    /** @param array<array-key, mixed> $body */
    private function responseHasJsonBody(array $body): void
    {
        $this->response = $this->response->withBody(
            $this->streamFactory->createStream(
                json_encode($body, JSON_THROW_ON_ERROR)
            )
        );
    }

    public function testThatTheRequestAndResponseAreAvailable(): void
    {
        $exception = RequestFailure::with($this->request, $this->response);
        self::assertSame($this->request, $exception->request());
        self::assertSame($this->response, $exception->response());
    }

    public function testExpectedMessageWhenTheResponseHasNoBody(): void
    {
        $exception = RequestFailure::with($this->request, $this->response);
        self::assertEquals(
            'A request error occurred and there was no message encoded in the response.',
            $exception->getMessage()
        );
    }

    public function testThePostmarkMessageAndCodeAreNullWhenTheResponseHasAnEmptyBody(): void
    {
        $exception = RequestFailure::with($this->request, $this->response);
        self::assertNull($exception->postmarkErrorMessage());
        self::assertNull($exception->postmarkErrorCode());
    }

    public function testThatTheExceptionCodeMatchesTheResponseHttpStatus(): void
    {
        $exception = RequestFailure::with($this->request, $this->response->withStatus(123));
        self::assertSame(123, $exception->getCode());
    }

    public function testThatThePostmarkErrorMessageAndCodeHaveTheExpectedValues(): void
    {
        $this->responseHasJsonBody([
            'Message' => 'Example Message',
            'ErrorCode' => 900,
        ]);
        $exception = RequestFailure::with($this->request, $this->response);
        self::assertEquals('Example Message', $exception->postmarkErrorMessage());
        self::assertEquals(900, $exception->postmarkErrorCode());
    }

    /** @return array<int, array{0: int, 1: string}> */
    public function specialErrorCodeProvider(): array
    {
        return [
            401 => [
                401,
                'Unauthorized: Missing or incorrect API token in header. '
                . 'Please verify that you used the correct token when you constructed your client.',
            ],
            500 => [
                500,
                'Internal Server Error: This is an issue with Postmark’s servers processing your request. '
                . 'In most cases the message is lost during the process, '
                . 'and Postmark is notified so that we can investigate the issue.',
            ],
            503 => [
                503,
                'The Postmark API is currently unavailable, please try your request later.',
            ],
            123 => [
                123,
                'A request error occurred and there was no message encoded in the response.',
            ],
        ];
    }

    /** @dataProvider specialErrorCodeProvider */
    public function testThatSpecialCaseHttpStatusCodesYieldTheExpectedMessage(int $code, string $expectedMessage): void
    {
        $exception = RequestFailure::with($this->request, $this->response->withStatus($code));
        self::assertEquals($expectedMessage, $exception->getMessage());
    }

    /** @return array<int, array{0: int, 1: int, 2: string}> */
    public function typicalErrorProvider(): array
    {
        return [
            409 => [
                422,
                409,
                'Content-Type and Accept headers must be set to application/json, text/json, or text/x-json.',
            ],
            10 => [
                422,
                10,
                'No Account or Server API tokens were supplied in the HTTP headers. Please add a header for either X-Postmark-Server-Token or X-Postmark-Account-Token.',
            ],
        ];
    }

    /** @dataProvider typicalErrorProvider */
    public function testTypicalApiErrorResponses(int $httpCode, int $code, string $expectedMessage): void
    {
        $this->responseHasJsonBody([
            'ErrorCode' => $code,
            'Message' => $expectedMessage,
        ]);

        $exception = RequestFailure::with($this->request, $this->response->withStatus($httpCode));
        self::assertEquals($expectedMessage, $exception->getMessage());
        self::assertEquals($expectedMessage, $exception->postmarkErrorMessage());
        self::assertSame($httpCode, $exception->getCode());
        self::assertSame($code, $exception->postmarkErrorCode());
    }
}
