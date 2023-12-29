<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundOpenStats
{
    public array $Days; // array of DatedOpenCount
    public int $Opens;
    public int $Unique;

    public function __construct(array $values = [])
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Opens = !empty($values['Opens']) ? $values['Opens'] : 0;
        $this->Unique = !empty($values['Unique']) ? $values['Unique'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $Days): PostmarkOutboundOpenStats
    {
        $tempArray = [];
        foreach ($Days as $value) {
            $temp = new DatedOpenCount($value);
            $tempArray[] = $temp;
        }
        $this->Days = $tempArray;

        return $this;
    }

    public function getOpens(): int
    {
        return $this->Opens;
    }

    public function setOpens(int $Opens): PostmarkOutboundOpenStats
    {
        $this->Opens = $Opens;

        return $this;
    }

    public function getUnique(): int
    {
        return $this->Unique;
    }

    public function setUnique(int $Unique): PostmarkOutboundOpenStats
    {
        $this->Unique = $Unique;

        return $this;
    }
}

class DatedOpenCount
{
    public string $Date;
    public int $Opens;
    public int $Unique;

    public function __construct(array $values = [])
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->Opens = !empty($values['Opens']) ? $values['Opens'] : 0;
        $this->Unique = !empty($values['Unique']) ? $values['Unique'] : 0;
    }

    public function getDate(): string
    {
        return $this->Date;
    }

    public function setDate(string $Date): DatedOpenCount
    {
        $this->Date = $Date;

        return $this;
    }

    public function getOpens(): int
    {
        return $this->Opens;
    }

    public function setOpens(int $Opens): DatedOpenCount
    {
        $this->Opens = $Opens;

        return $this;
    }

    public function getUnique(): int
    {
        return $this->Unique;
    }

    public function setUnique(int $Unique): DatedOpenCount
    {
        $this->Unique = $Unique;

        return $this;
    }
}
