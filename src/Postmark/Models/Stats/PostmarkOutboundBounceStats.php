<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundBounceStats
{
    public array $Days; // DatedBounceCount object
    public int $HardBounce;
    public int $SMTPApiError;
    public int $SoftBounce;
    public int $Transient;

    public function __construct(array $values)
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->HardBounce = !empty($values['HardBounce']) ? $values['HardBounce'] : 0;
        $this->SMTPApiError = !empty($values['SMTPApiError']) ? $values['SMTPApiError'] : 0;
        $this->SoftBounce = !empty($values['SoftBounce']) ? $values['SoftBounce'] : 0;
        $this->Transient = !empty($values['Transient']) ? $values['Transient'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $datedBounceCount): PostmarkOutboundBounceStats
    {
        $tempArray = [];
        foreach ($datedBounceCount as $value) {
            $temp = new DatedBounceCount($value);
            $tempArray[] = $temp;
        }
        $this->Days = $tempArray;

        return $this;
    }

    public function getHardBounce(): int
    {
        return $this->HardBounce;
    }

    public function setHardBounce(int $HardBounce): PostmarkOutboundBounceStats
    {
        $this->HardBounce = $HardBounce;

        return $this;
    }

    public function getSMTPApiError(): int
    {
        return $this->SMTPApiError;
    }

    public function setSMTPApiError(int $SMTPApiError): PostmarkOutboundBounceStats
    {
        $this->SMTPApiError = $SMTPApiError;

        return $this;
    }

    public function getSoftBounce(): int
    {
        return $this->SoftBounce;
    }

    public function setSoftBounce(int $SoftBounce): PostmarkOutboundBounceStats
    {
        $this->SoftBounce = $SoftBounce;

        return $this;
    }

    public function getTransient(): int
    {
        return $this->Transient;
    }

    public function setTransient(int $Transient): PostmarkOutboundBounceStats
    {
        $this->Transient = $Transient;

        return $this;
    }
}

class DatedBounceCount
{
    public string $Date; // DatedBounceCount object
    public int $HardBounce;
    public int $SoftBounce;

    public function __construct(array $values)
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->HardBounce = !empty($values['HardBounce']) ? $values['HardBounce'] : 0;
        $this->SoftBounce = !empty($values['SoftBounce']) ? $values['SoftBounce'] : 0;
    }

    /**
     * @return string
     */
    public function getDate(): mixed
    {
        return $this->Date;
    }

    public function setDate(string $Date): DatedBounceCount
    {
        $this->Date = $Date;

        return $this;
    }

    public function getHardBounce(): int
    {
        return $this->HardBounce;
    }

    public function setHardBounce(int $HardBounce): DatedBounceCount
    {
        $this->HardBounce = $HardBounce;

        return $this;
    }

    public function getSoftBounce(): int
    {
        return $this->SoftBounce;
    }

    public function setSoftBounce(int $SoftBounce): DatedBounceCount
    {
        $this->SoftBounce = $SoftBounce;

        return $this;
    }
}
