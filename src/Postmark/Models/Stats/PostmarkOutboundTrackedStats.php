<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundTrackedStats
{
    public array $Days; // DatedTrackedCount object
    public int $Tracked;

    public function __construct(array $values)
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Tracked = !empty($values['Tracked']) ? $values['Tracked'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $datedTrackedCount): PostmarkOutboundTrackedStats
    {
        $tempArray = [];
        foreach ($datedTrackedCount as $value) {
            $temp = new DatedTrackedCount($value);
            $tempArray[] = $temp;
        }
        $this->Days = $tempArray;

        return $this;
    }

    public function getTracked(): int
    {
        return $this->Tracked;
    }

    public function setTracked(int $Tracked): PostmarkOutboundTrackedStats
    {
        $this->Tracked = $Tracked;

        return $this;
    }
}

class DatedTrackedCount
{
    public string $Date;
    public int $Tracked;

    public function __construct(array $values)
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->Tracked = !empty($values['Tracked']) ? $values['Tracked'] : 0;
    }

    public function getDate(): string
    {
        return $this->Date;
    }

    public function setDate(string $Date): DatedTrackedCount
    {
        $this->Date = $Date;

        return $this;
    }

    public function getTracked(): int
    {
        return $this->Tracked;
    }

    public function setTracked(int $Tracked): DatedTrackedCount
    {
        $this->Tracked = $Tracked;

        return $this;
    }
}
