<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundPlatformStats
{
    public array $Days; // DatedSendCount object
    public int $Sent;

    public function __construct(array $values)
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Sent = !empty($values['Sent']) ? $values['Sent'] : 0;
    }
}

