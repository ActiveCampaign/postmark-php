<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\Models\Webhooks;

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
    public function knownClassProvider(): array
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

    /**
     * @param class-string<WebhookConfiguration> $class
     *
     * @dataProvider knownClassProvider
     */
    public function testThatByDefaultAllTypesAreDisabled(string $class): void
    {
        $object = new $class();
        assert($object instanceof WebhookConfiguration);
        self::assertFalse($object->getEnabled());
    }
}
