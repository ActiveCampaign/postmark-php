<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundSpamComplaintStats
{
    public array $Days; // DatedPlatformCount object
    public int $Desktop;
    public int $Mobile;
    public int $Unknown;
    public int $WebMail;

    public function __construct(array $values)
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Desktop = !empty($values['Desktop']) ? $values['Desktop'] : 0;
        $this->Mobile = !empty($values['Mobile']) ? $values['Mobile'] : 0;
        $this->Unknown = !empty($values['Unknown']) ? $values['Unknown'] : 0;
        $this->WebMail = !empty($values['WebMail']) ? $values['WebMail'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $datedPlatformCount): PostmarkOutboundSpamComplaintStats
    {
        $tempArray = [];
        foreach ($datedPlatformCount as $value) {
            $temp = new DatedPlatformCount($value);
            $tempArray[] = $temp;
        }
        $this->Days = $tempArray;

        return $this;
    }

    public function getDesktop(): int
    {
        return $this->Desktop;
    }

    public function setDesktop(int $Desktop): PostmarkOutboundSpamComplaintStats
    {
        $this->Desktop = $Desktop;

        return $this;
    }

    public function getMobile(): int
    {
        return $this->Mobile;
    }

    public function setMobile(int $Mobile): PostmarkOutboundSpamComplaintStats
    {
        $this->Mobile = $Mobile;

        return $this;
    }

    public function getUnknown(): int
    {
        return $this->Unknown;
    }

    public function setUnknown(int $Unknown): PostmarkOutboundSpamComplaintStats
    {
        $this->Unknown = $Unknown;

        return $this;
    }

    public function getWebMail(): int
    {
        return $this->WebMail;
    }

    public function setWebMail(int $WebMail): PostmarkOutboundSpamComplaintStats
    {
        $this->WebMail = $WebMail;

        return $this;
    }
}

class DatedPlatformCount
{
    public string $Date;
    public int $Desktop;
    public int $Mobile;
    public int $Unknown;
    public int $WebMail;

    public function __construct(array $values)
    {
        $this->Date = !empty($values['Date']) ? $values['Date'] : '';
        $this->Desktop = !empty($values['Desktop']) ? $values['Desktop'] : 0;
        $this->Mobile = !empty($values['Mobile']) ? $values['Mobile'] : 0;
        $this->Unknown = !empty($values['Unknown']) ? $values['Unknown'] : 0;
        $this->WebMail = !empty($values['WebMail']) ? $values['WebMail'] : 0;
    }

    public function getDate(): string
    {
        return $this->Date;
    }

    public function setDate(string $Date): DatedPlatformCount
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDesktop(): int
    {
        return $this->Desktop;
    }

    public function setDesktop(int $Desktop): DatedPlatformCount
    {
        $this->Desktop = $Desktop;

        return $this;
    }

    public function getMobile(): int
    {
        return $this->Mobile;
    }

    public function setMobile(int $Mobile): DatedPlatformCount
    {
        $this->Mobile = $Mobile;

        return $this;
    }

    public function getUnknown(): int
    {
        return $this->Unknown;
    }

    public function setUnknown(int $Unknown): DatedPlatformCount
    {
        $this->Unknown = $Unknown;

        return $this;
    }

    public function getWebMail(): int
    {
        return $this->WebMail;
    }

    public function setWebMail(int $WebMail): DatedPlatformCount
    {
        $this->WebMail = $WebMail;

        return $this;
    }
}
