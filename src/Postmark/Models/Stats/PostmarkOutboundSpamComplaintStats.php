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

    /**
     * @return int
     */
    public function getDesktop(): int
    {
        return $this->Desktop;
    }

    /**
     * @param int $Desktop
     * @return PostmarkOutboundSpamComplaintStats
     */
    public function setDesktop(int $Desktop): PostmarkOutboundSpamComplaintStats
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
     * @return PostmarkOutboundSpamComplaintStats
     */
    public function setMobile(int $Mobile): PostmarkOutboundSpamComplaintStats
    {
        $this->Mobile = $Mobile;
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
     * @return PostmarkOutboundSpamComplaintStats
     */
    public function setUnknown(int $Unknown): PostmarkOutboundSpamComplaintStats
    {
        $this->Unknown = $Unknown;
        return $this;
    }

    /**
     * @return int
     */
    public function getWebMail(): int
    {
        return $this->WebMail;
    }

    /**
     * @param int $WebMail
     * @return PostmarkOutboundSpamComplaintStats
     */
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

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->Date;
    }

    /**
     * @param string $Date
     * @return DatedPlatformCount
     */
    public function setDate(string $Date): DatedPlatformCount
    {
        $this->Date = $Date;
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
     * @return DatedPlatformCount
     */
    public function setDesktop(int $Desktop): DatedPlatformCount
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
     * @return DatedPlatformCount
     */
    public function setMobile(int $Mobile): DatedPlatformCount
    {
        $this->Mobile = $Mobile;
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
     * @return DatedPlatformCount
     */
    public function setUnknown(int $Unknown): DatedPlatformCount
    {
        $this->Unknown = $Unknown;
        return $this;
    }

    /**
     * @return int
     */
    public function getWebMail(): int
    {
        return $this->WebMail;
    }

    /**
     * @param int $WebMail
     * @return DatedPlatformCount
     */
    public function setWebMail(int $WebMail): DatedPlatformCount
    {
        $this->WebMail = $WebMail;
        return $this;
    }

}