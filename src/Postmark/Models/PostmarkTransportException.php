<?php

namespace Postmark\Models;

use Throwable;

/**
 * Thrown when the request never produced an HTTP response.
 *
 * DNS failure, connection refused, TLS handshake failure, timeout, socket error —
 * anything where the API was not reached. A response that arrives and carries an
 * error is a plain {@see PostmarkException} instead.
 *
 * This exists so callers never have to catch their HTTP client's exception types.
 * The SDK previously documented `@throws \GuzzleHttp\Exception\GuzzleException`,
 * which made Guzzle's hierarchy part of the public contract — and Guzzle 8
 * reclassified exactly that family, so `catch (ConnectException)` around a send
 * silently stopped matching a timeout.
 *
 * The class is deliberately free of any Guzzle reference: the client normalises
 * the failure to one of the KIND_* values before constructing this, so all
 * version-specific knowledge lives in one place.
 */
class PostmarkTransportException extends PostmarkException
{
    /** No response within the configured timeout. */
    public const KIND_TIMEOUT = 'timeout';

    /** Connection could not be established, or was lost before a response. */
    public const KIND_CONNECTION = 'connection';

    /** Reached the transport layer, but the cause could not be determined. */
    public const KIND_UNKNOWN = 'unknown';

    private string $kind;

    public function __construct(string $message, ?Throwable $previous = null, string $kind = self::KIND_UNKNOWN)
    {
        parent::__construct($message, 0, $previous);

        // PostmarkException redeclares $message as a public property, so the value
        // handed to the parent constructor is not what getMessage() reports unless
        // it is assigned here too.
        $this->message = $message;
        $this->kind = $kind;
    }

    /**
     * The request was not answered within the configured timeout.
     *
     * Retry is at-least-once, not safe-to-repeat: a timeout means no response was
     * seen, which does not prove the message was rejected.
     */
    public function isTimeout(): bool
    {
        return self::KIND_TIMEOUT === $this->kind;
    }

    /**
     * The connection failed before a response could be produced.
     *
     * Safe to retry — the request did not complete.
     */
    public function isConnectionFailure(): bool
    {
        return self::KIND_CONNECTION === $this->kind;
    }

    /** One of the KIND_* constants. */
    public function getKind(): string
    {
        return $this->kind;
    }
}
