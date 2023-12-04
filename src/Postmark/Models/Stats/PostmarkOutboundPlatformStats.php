<?php

namespace Postmark\Models\Stats;

use Postmark\Models\Stats\DatedPlatformCount;

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

    /**
     * @return array
     */
    public function getDays(): array
    {
        return $this->Days;
    }

    /**
     * @param array $Days
     * @return PostmarkOutboundPlatformStats
     */
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

    /**
     * @return int
     */
    public function getDesktop(): int
    {
        return $this->Desktop;
    }

    /**
     * @param int $Desktop
     * @return PostmarkOutboundPlatformStats
     */
    public function setDesktop(int $Desktop): PostmarkOutboundPlatformStats
    {
        $this->Desktop = $Desktop;
        return $this;
    }

    /**
     * @return int
     */
    public function getMobile(): int
    {
        return $this->Mobile;
    }

    /**
     * @param int $Mobile
     * @return PostmarkOutboundPlatformStats
     */
    public function setMobile(int $Mobile): PostmarkOutboundPlatformStats
    {
        $this->Mobile = $Mobile;
        return $this;
    }

    /**
     * @return int
     */
    public function getWebmail(): int
    {
        return $this->Webmail;
    }

    /**
     * @param int $Webmail
     * @return PostmarkOutboundPlatformStats
     */
    public function setWebmail(int $Webmail): PostmarkOutboundPlatformStats
    {
        $this->Webmail = $Webmail;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnknown(): int
    {
        return $this->Unknown;
    }

    /**
     * @param int $Unknown
     * @return PostmarkOutboundPlatformStats
     */
    public function setUnknown(int $Unknown): PostmarkOutboundPlatformStats
    {
        $this->Unknown = $Unknown;
        return $this;
    }
}