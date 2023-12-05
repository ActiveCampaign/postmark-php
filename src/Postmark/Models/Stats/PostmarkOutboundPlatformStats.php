<?php

namespace Postmark\Models\Stats;

class PostmarkOutboundPlatformStats
{
    public array $Days; // DatedPlatformCount object
    public int $Desktop;
    public int $Mobile;
    public int $Webmail;
    public int $Unknown;

    public function __construct(array $values)
    {
        !empty($values['Days']) ? $this->setDays($values['Days']) : $this->setDays([]);
        $this->Desktop = !empty($values['Desktop']) ? $values['Desktop'] : 0;
        $this->Mobile = !empty($values['Mobile']) ? $values['Mobile'] : 0;
        $this->Webmail = !empty($values['Webmail']) ? $values['Webmail'] : 0;
        $this->Unknown = !empty($values['Unknown']) ? $values['Unknown'] : 0;
    }

    public function getDays(): array
    {
        return $this->Days;
    }

    public function setDays(array $Days): PostmarkOutboundPlatformStats
    {
        $tempArray = [];
        foreach ($Days as $value) {
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

    public function setDesktop(int $Desktop): PostmarkOutboundPlatformStats
    {
        $this->Desktop = $Desktop;

        return $this;
    }

    public function getMobile(): int
    {
        return $this->Mobile;
    }

    public function setMobile(int $Mobile): PostmarkOutboundPlatformStats
    {
        $this->Mobile = $Mobile;

        return $this;
    }

    public function getWebmail(): int
    {
        return $this->Webmail;
    }

    public function setWebmail(int $Webmail): PostmarkOutboundPlatformStats
    {
        $this->Webmail = $Webmail;

        return $this;
    }

    public function getUnknown(): int
    {
        return $this->Unknown;
    }

    public function setUnknown(int $Unknown): PostmarkOutboundPlatformStats
    {
        $this->Unknown = $Unknown;

        return $this;
    }
}
