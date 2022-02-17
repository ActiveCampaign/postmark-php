<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

use JsonSerializable;

interface WebhookConfiguration extends JsonSerializable
{
    public function getEnabled(): bool;
}
