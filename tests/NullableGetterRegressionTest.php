<?php

namespace Postmark\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Postmark\Models\PostmarkMessage;
use Postmark\Models\PostmarkOpen;
use Postmark\Models\Webhooks\WebhookConfiguration;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;

/**
 * Getters backed by nullable properties must declare nullable return types.
 *
 * Each of these threw "TypeError: Return value must be of type X, null returned"
 * on a valid API response that simply omitted the field — the constructors
 * explicitly assign null when the key is absent, so the getter was guaranteed to
 * fatal. Reported from production against getGeo() on a broadcast-stream message.
 *
 * No credentials required: these construct the models directly from the array
 * shape the API returns.
 */
class NullableGetterRegressionTest extends TestCase
{
    /** An open event with no geo, client or OS data — the reported case. */
    public function testPostmarkOpenGettersReturnNullWhenTheApiOmitsThem(): void
    {
        $open = new PostmarkOpen([
            'MessageID' => '454ef8fc-da5f-4662-b6d6-74a54d78504d',
            'ReceivedAt' => '2026-08-06T00:00:00Z',
        ]);

        $this->assertNull($open->getGeo());
        $this->assertNull($open->getClient());
        $this->assertNull($open->getOS());
    }

    /** PostmarkClick already declared these loosely; Open must agree with it. */
    public function testOpenAndClickAgreeOnNullability(): void
    {
        foreach (['getGeo', 'getClient', 'getOS'] as $getter) {
            $type = (new \ReflectionMethod(PostmarkOpen::class, $getter))->getReturnType();

            $this->assertNotNull($type, "PostmarkOpen::{$getter}() should declare a return type.");
            $this->assertTrue(
                $type->allowsNull(),
                "PostmarkOpen::{$getter}() must allow null; the constructor assigns null when the key is absent."
            );
        }
    }

    public function testMessageBaseGettersReturnNullBeforeTheyAreSet(): void
    {
        $message = new PostmarkMessage();

        $this->assertNull($message->getMetadata());
        $this->assertNull($message->getMessageStream());
    }

    /**
     * Build() declares `?HttpAuth $HttpAuth = null`, so a webhook configured
     * without basic auth — the common case — returned null from a getter that
     * promised HttpAuth.
     */
    public function testWebhookConfigurationHttpAuthReturnsNullWhenUnset(): void
    {
        $configuration = new WebhookConfiguration(
            1,
            'https://example.com/hook',
            'outbound',
            null,
            [],
            new WebhookConfigurationTriggers()
        );

        $this->assertNull($configuration->getHttpAuth());
    }

    /**
     * MessageIDs are GUIDs. getBounces() declared ?int for this filter from
     * c7a4371 (shipped v5.0.1 onward), which made it impossible to use.
     */
    public function testGetBouncesAcceptsAGuidMessageId(): void
    {
        $parameter = null;

        foreach ((new \ReflectionMethod(\Postmark\PostmarkClient::class, 'getBounces'))->getParameters() as $candidate) {
            if ('messageID' === $candidate->getName()) {
                $parameter = $candidate;

                break;
            }
        }

        $this->assertNotNull($parameter, 'getBounces() must still accept a $messageID filter.');
        $this->assertSame('?string', (string) $parameter->getType());
    }
}
