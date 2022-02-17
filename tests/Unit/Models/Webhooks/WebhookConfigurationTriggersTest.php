<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models\Webhooks;

use PHPUnit\Framework\TestCase;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationDeliveryTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSubscriptionChange;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;

use function json_decode;
use function json_encode;

use const JSON_THROW_ON_ERROR;

class WebhookConfigurationTriggersTest extends TestCase
{
    public function testThatZeroArgumentsYieldNullsAllRound(): void
    {
        $hooks = new WebhookConfigurationTriggers();

        self::assertNull($hooks->getBounceSettings());
        self::assertNull($hooks->getClickSettings());
        self::assertNull($hooks->getDeliverySettings());
        self::assertNull($hooks->getOpenSettings());
        self::assertNull($hooks->getSpamComplaintSettings());
        self::assertNull($hooks->getSubscriptionChangeSettings());
    }

    public function testThatZeroArgumentsYieldsEmptyArrayForSerialisation(): void
    {
        $hooks = new WebhookConfigurationTriggers();
        self::assertSame([], $hooks->jsonSerialize());
    }

    public function testThatSerializedOutputContainsAnyNonNullArgument(): void
    {
        $open = new WebhookConfigurationOpenTrigger();
        $hooks = new WebhookConfigurationTriggers($open);
        self::assertSame($open, $hooks->getOpenSettings());
        $array = $hooks->jsonSerialize();
        self::assertArrayHasKey('Open', $array);
        self::assertSame($open, $array['Open']);
        self::assertCount(1, $array);
    }

    public function testThatSerialisationContainsTheExpectedValues(): void
    {
        $hooks = new WebhookConfigurationTriggers(
            new WebhookConfigurationOpenTrigger(),
            new WebhookConfigurationClickTrigger(),
            new WebhookConfigurationDeliveryTrigger(),
            new WebhookConfigurationBounceTrigger(),
            new WebhookConfigurationSpamComplaintTrigger(),
            new WebhookConfigurationSubscriptionChange(),
        );

        $decoded = json_decode(json_encode($hooks, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);
        $expectKeys = [
            'Open',
            'Click',
            'Delivery',
            'Bounce',
            'SpamComplaint',
            'SubscriptionChange',
        ];

        foreach ($expectKeys as $key) {
            self::assertArrayHasKey($key, $decoded);
            self::assertIsArray($decoded[$key], 'Each type of hook configuration should be encoded to json');
            self::assertContainsOnly('bool', $decoded[$key], true, 'Hook configurations can only contain boolean values');
        }
    }
}
