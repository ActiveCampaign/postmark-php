<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundOpenStats
{
    public array $Days; // array of DatedOpenCount
    public int $Opens;
    public int $Unique;

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Opens = !empty($values['Opens']) ? $values['Opens'] : 0;
        $this->Unique = !empty($values['Unique']) ? $values['Unique'] : 0;
    }

    /**
     * @return array
     */
    public function getDays(): array
    {
        return $this->Days;
    }

    /**
     * @param array $Days
     * @return PostmarkOutboundOpenStats
     */
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

    /**
     * @return int
     */
    public function getOpens(): int
    {
        return $this->Opens;
    }

    /**
     * @param int $Opens
     * @return PostmarkOutboundOpenStats
     */
    public function setOpens(int $Opens): PostmarkOutboundOpenStats
    {
        $this->Opens = $Opens;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnique(): int
    {
        return $this->Unique;
    }

    /**
     * @param int $Unique
     * @return PostmarkOutboundOpenStats
     */
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

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->Opens = !empty($values['Opens']) ? $values['Opens'] : 0;
        $this->Unique = !empty($values['Unique']) ? $values['Unique'] : 0;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->Date;
    }

    /**
     * @param string $Date
     * @return DatedOpenCount
     */
    public function setDate(string $Date): DatedOpenCount
    {
        $this->Date = $Date;
        return $this;
    }

    /**
     * @return int
     */
    public function getOpens(): int
    {
        return $this->Opens;
    }

    /**
     * @param int $Opens
     * @return DatedOpenCount
     */
    public function setOpens(int $Opens): DatedOpenCount
    {
        $this->Opens = $Opens;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnique(): int
    {
        return $this->Unique;
    }

    /**
     * @param int $Unique
     * @return DatedOpenCount
     */
    public function setUnique(int $Unique): DatedOpenCount
    {
        $this->Unique = $Unique;
        return $this;
    }

}