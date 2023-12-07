<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundClickStats
{
    public array $Days; // array of DatedClickCount
    public int $Clicks;
    public int $Unique;

    public function __construct(array $values)
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Clicks = !empty($values['Clicks']) ? $values['Clicks'] : 0;
        $this->Unique = !empty($values['Unique']) ? $values['Unique'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $days): PostmarkOutboundClickStats
    {
        $tempArray = [];
        foreach ($days as $value) {
            $temp = new DatedClickCount($value);
            $tempArray[] = $temp;
        }
        $this->Days = $tempArray;

        return $this;
    }

    public function getClicks(): int
    {
        return $this->Clicks;
    }

    public function setClicks(int $Clicks): PostmarkOutboundClickStats
    {
        $this->Clicks = $Clicks;

        return $this;
    }

    public function getUnique(): int
    {
        return $this->Unique;
    }

    public function setUnique(int $Unique): PostmarkOutboundClickStats
    {
        $this->Unique = $Unique;

        return $this;
    }
}

class DatedClickCount
{
    public string $Date;
    public int $Clicks;
    public int $Unique;

    public function __construct(array $values)
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->Clicks = !empty($values['Clicks']) ? $values['Clicks'] : 0;
        $this->Unique = !empty($values['Unique']) ? $values['Unique'] : 0;
    }

    public function getDate(): string
    {
        return $this->Date;
    }

    public function setDate(string $Date): DatedClickCount
    {
        $this->Date = $Date;

        return $this;
    }

    public function getClicks(): int
    {
        return $this->Clicks;
    }

    public function setClicks(int $Clicks): DatedClickCount
    {
        $this->Clicks = $Clicks;

        return $this;
    }

    public function getUnique(): int
    {
        return $this->Unique;
    }

    public function setUnique(int $Unique): DatedClickCount
    {
        $this->Unique = $Unique;

        return $this;
    }
}
