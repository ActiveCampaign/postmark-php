<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundSentStats
{
    public array $Days; // DatedSendCount object
    public int $Sent;

    public function __construct(array $values)
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Sent = !empty($values['Sent']) ? $values['Sent'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $datedSendCount): PostmarkOutboundSentStats
    {
        $tempArray = [];
        foreach ($datedSendCount as $value) {
            $temp = new DatedSendCount($value);
            $tempArray[] = $temp;
        }
        $this->Days = $tempArray;

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
