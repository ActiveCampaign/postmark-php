<?php

namespace Postmark\Models;

class PostmarkOutboundSentStats
{
    public array $Days;
    public int $Sent;

    public function __construct(array $values)
    {
        $this->Days = !empty($values['Days']) ? $values['Days'] : [];
        $this->Sent = !empty($values['Sent']) ? $values['Sent'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $Days): PostmarkOutboundSentStats
    {
        $this->Days = $Days;

        return $this;
    }

    public function getSent(): int
    {
        return $this->Sent;
    }

    public function setSent(int $Sent): PostmarkOutboundSentStats
    {
        $this->Sent = $Sent;

        return $this;
    }
}

class DatedSendCount
{
    public string $Date;
    public int $Sent;

    public function __construct(array $values)
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->Sent = !empty($values['Sent']) ? $values['Sent'] : 0;
    }

    public function getDate(): string
    {
        return $this->Date;
    }

    public function setDate(string $Date): DatedSendCount
    {
        $this->Date = $Date;

        return $this;
    }

    public function getSent(): int
    {
        return $this->Sent;
    }

    public function setSent(int $Sent): DatedSendCount
    {
        $this->Sent = $Sent;

        return $this;
    }
}
