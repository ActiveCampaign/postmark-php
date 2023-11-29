<?php

namespace Postmark\Models;

class PostmarkOutboundSentStats
{
    public array $Days;
    public int $Sent;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->Days = !empty($values['Days']) ? $values['Days'] : [];
        $this->Sent = !empty($values['Sent']) ? $values['Sent'] : 0;
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
     * @return PostmarkOutboundSentStats
     */
    public function setDays(array $Days): PostmarkOutboundSentStats
    {
        $this->Days = $Days;
        return $this;
    }

    /**
     * @return int
     */
    public function getSent(): int
    {
        return $this->Sent;
    }

    /**
     * @param int $Sent
     * @return PostmarkOutboundSentStats
     */
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

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->Sent = !empty($values['Sent']) ? $values['Sent'] : 0;
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
     * @return DatedSendCount
     */
    public function setDate(string $Date): DatedSendCount
    {
        $this->Date = $Date;
        return $this;
    }

    /**
     * @return int
     */
    public function getSent(): int
    {
        return $this->Sent;
    }

    /**
     * @param int $Sent
     * @return DatedSendCount
     */
    public function setSent(int $Sent): DatedSendCount
    {
        $this->Sent = $Sent;
        return $this;
    }
}