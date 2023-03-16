<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models\Webhooks;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Postmark\Models\Webhooks\WebhookConfiguration;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationDeliveryTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSubscriptionChange;

use function assert;

class WebhookConfigurationTest extends TestCase
{
    /** @return array<array-key, array{0: class-string<WebhookConfiguration>}> */
    public static function knownClassProvider(): array
    {
        return [
            [WebhookConfigurationBounceTrigger::class],
            [WebhookConfigurationClickTrigger::class],
            [WebhookConfigurationDeliveryTrigger::class],
            [WebhookConfigurationOpenTrigger::class],
            [WebhookConfigurationSpamComplaintTrigger::class],
            [WebhookConfigurationSubscriptionChange::class],
        ];
    }

    /** @param class-string<WebhookConfiguration> $class */
    #[DataProvider('knownClassProvider')]
    public function testThatByDefaultAllTypesAreDisabled(string $class): void
    {
        $object = new $class();
        assert($object instanceof WebhookConfiguration);
        self::assertFalse($object->getEnabled());
    }

    public function testBounceSettings(): void
    {
        $bounce = new WebhookConfigurationBounceTrigger(false, true);
        self::assertFalse($bounce->getEnabled());
        self::assertTrue($bounce->getIncludeContent());
        $bounce = new WebhookConfigurationBounceTrigger(true, false);
        self::assertTrue($bounce->getEnabled());
        self::assertFalse($bounce->getIncludeContent());
    }

    public function testClickSettings(): void
    {
        $click = new WebhookConfigurationClickTrigger(true);
        self::assertTrue($click->getEnabled());
        $click = new WebhookConfigurationClickTrigger(false);
        self::assertFalse($click->getEnabled());
    }

    public function testDeliverySettings(): void
    {
        $delivery = new WebhookConfigurationDeliveryTrigger(true);
        self::assertTrue($delivery->getEnabled());
        $delivery = new WebhookConfigurationDeliveryTrigger(false);
        self::assertFalse($delivery->getEnabled());
    }

    public function testOpenSettings(): void
    {
        $open = new WebhookConfigurationOpenTrigger(true, false);
        self::assertTrue($open->getEnabled());
        self::assertFalse($open->getPostFirstOpenOnly());
        $open = new WebhookConfigurationOpenTrigger(false, true);
        self::assertFalse($open->getEnabled());
        self::assertTrue($open->getPostFirstOpenOnly());
    }

    public function testSpamComplaintSettings(): void
    {
        $spam = new WebhookConfigurationSpamComplaintTrigger(true, false);
        self::assertTrue($spam->getEnabled());
        self::assertFalse($spam->getIncludeContent());
        $spam = new WebhookConfigurationSpamComplaintTrigger(false, true);
        self::assertFalse($spam->getEnabled());
        self::assertTrue($spam->getIncludeContent());
    }

    public function testSubscriptionChangeSettings(): void
    {
        $subChange = new WebhookConfigurationSubscriptionChange(true);
        self::assertTrue($subChange->getEnabled());
        $subChange = new WebhookConfigurationSubscriptionChange(false);
        self::assertFalse($subChange->getEnabled());
    }
}
