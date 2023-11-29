<?php

namespace Postmark\Models;

use PhpParser\Node\Expr\Cast\Double;

class PostmarkOutboundOverviewStats
{
    public int $Sent;
    public int $Bounced;
    public int $SMTPApiErrors;
    public float $BounceRate;
    public int $SpamComplaints;
    public float $SpamComplaintsRate;
    public int $Opens;
    public int $UniqueOpens;
    public int $Tracked;
    public int $WithClientRecorded;
    public int $WithPlatformRecorded;
    public int $WithReadTimeRecorded;
    public int $TotalClicks;
    public int $UniqueLinksClicked;
    public int $TotalTrackedLinksSent;
    public int $WithLinkTracking;
    public int $WithOpenTracking;

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->Sent = !empty($values['Sent']) ? $values['Sent'] : 0;
        $this->Bounced = !empty($values['Bounced']) ? $values['Bounced'] : 0;
        $this->SMTPApiErrors = !empty($values['SMTPApiErrors']) ? $values['SMTPApiErrors'] : 0;
        $this->BounceRate = !empty($values['BounceRate']) ? $values['BounceRate'] : 0;
        $this->SpamComplaints = !empty($values['SpamComplaints']) ? $values['SpamComplaints'] : 0;
        $this->SpamComplaintsRate = !empty($values['SpamComplaintsRate']) ? $values['SpamComplaintsRate'] : 0;
        $this->Opens = !empty($values['Opens']) ? $values['Opens'] : 0;
        $this->UniqueOpens = !empty($values['UniqueOpens']) ? $values['UniqueOpens'] : 0;
        $this->Tracked = !empty($values['Tracked']) ? $values['Tracked'] : 0;
        $this->WithClientRecorded = !empty($values['WithClientRecorded']) ? $values['WithClientRecorded'] : 0;
        $this->WithPlatformRecorded = !empty($values['WithPlatformRecorded']) ? $values['WithPlatformRecorded'] : 0;
        $this->WithReadTimeRecorded = !empty($values['WithReadTimeRecorded']) ? $values['WithReadTimeRecorded'] : 0;
        $this->TotalClicks = !empty($values['TotalClicks']) ? $values['TotalClicks'] : 0;
        $this->UniqueLinksClicked = !empty($values['UniqueLinksClicked']) ? $values['UniqueLinksClicked'] : 0;
        $this->TotalTrackedLinksSent = !empty($values['TotalTrackedLinksSent']) ? $values['TotalTrackedLinksSent'] : 0;
        $this->WithLinkTracking = !empty($values['WithLinkTracking']) ? $values['WithLinkTracking'] : 0;
        $this->WithOpenTracking = !empty($values['WithOpenTracking']) ? $values['WithOpenTracking'] : 0;
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
     * @return PostmarkOutboundOverviewStats
     */
    public function setSent(int $Sent): PostmarkOutboundOverviewStats
    {
        $this->Sent = $Sent;
        return $this;
    }

    /**
     * @return int
     */
    public function getBounced(): int
    {
        return $this->Bounced;
    }

    /**
     * @param int $Bounced
     * @return PostmarkOutboundOverviewStats
     */
    public function setBounced(int $Bounced): PostmarkOutboundOverviewStats
    {
        $this->Bounced = $Bounced;
        return $this;
    }

    /**
     * @return int
     */
    public function getSMTPApiErrors(): int
    {
        return $this->SMTPApiErrors;
    }

    /**
     * @param int $SMTPApiErrors
     * @return PostmarkOutboundOverviewStats
     */
    public function setSMTPApiErrors(int $SMTPApiErrors): PostmarkOutboundOverviewStats
    {
        $this->SMTPApiErrors = $SMTPApiErrors;
        return $this;
    }

    /**
     * @return float
     */
    public function getBounceRate(): float
    {
        return $this->BounceRate;
    }

    /**
     * @param float $BounceRate
     * @return PostmarkOutboundOverviewStats
     */
    public function setBounceRate(float $BounceRate): PostmarkOutboundOverviewStats
    {
        $this->BounceRate = $BounceRate;
        return $this;
    }

    /**
     * @return int
     */
    public function getSpamComplaints(): int
    {
        return $this->SpamComplaints;
    }

    /**
     * @param int $SpamComplaints
     * @return PostmarkOutboundOverviewStats
     */
    public function setSpamComplaints(int $SpamComplaints): PostmarkOutboundOverviewStats
    {
        $this->SpamComplaints = $SpamComplaints;
        return $this;
    }

    /**
     * @return float
     */
    public function getSpamComplaintsRate(): float
    {
        return $this->SpamComplaintsRate;
    }

    /**
     * @param float $SpamComplaintsRate
     * @return PostmarkOutboundOverviewStats
     */
    public function setSpamComplaintsRate(float $SpamComplaintsRate): PostmarkOutboundOverviewStats
    {
        $this->SpamComplaintsRate = $SpamComplaintsRate;
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
     * @return PostmarkOutboundOverviewStats
     */
    public function setOpens(int $Opens): PostmarkOutboundOverviewStats
    {
        $this->Opens = $Opens;
        return $this;
    }

    /**
     * @return int
     */
    public function getUniqueOpens(): int
    {
        return $this->UniqueOpens;
    }

    /**
     * @param int $UniqueOpens
     * @return PostmarkOutboundOverviewStats
     */
    public function setUniqueOpens(int $UniqueOpens): PostmarkOutboundOverviewStats
    {
        $this->UniqueOpens = $UniqueOpens;
        return $this;
    }

    /**
     * @return int
     */
    public function getTracked(): int
    {
        return $this->Tracked;
    }

    /**
     * @param int $Tracked
     * @return PostmarkOutboundOverviewStats
     */
    public function setTracked(int $Tracked): PostmarkOutboundOverviewStats
    {
        $this->Tracked = $Tracked;
        return $this;
    }

    /**
     * @return int
     */
    public function getWithClientRecorded(): int
    {
        return $this->WithClientRecorded;
    }

    /**
     * @param int $WithClientRecorded
     * @return PostmarkOutboundOverviewStats
     */
    public function setWithClientRecorded(int $WithClientRecorded): PostmarkOutboundOverviewStats
    {
        $this->WithClientRecorded = $WithClientRecorded;
        return $this;
    }

    /**
     * @return int
     */
    public function getWithPlatformRecorded(): int
    {
        return $this->WithPlatformRecorded;
    }

    /**
     * @param int $WithPlatformRecorded
     * @return PostmarkOutboundOverviewStats
     */
    public function setWithPlatformRecorded(int $WithPlatformRecorded): PostmarkOutboundOverviewStats
    {
        $this->WithPlatformRecorded = $WithPlatformRecorded;
        return $this;
    }

    /**
     * @return int
     */
    public function getWithReadTimeRecorded(): int
    {
        return $this->WithReadTimeRecorded;
    }

    /**
     * @param int $WithReadTimeRecorded
     * @return PostmarkOutboundOverviewStats
     */
    public function setWithReadTimeRecorded(int $WithReadTimeRecorded): PostmarkOutboundOverviewStats
    {
        $this->WithReadTimeRecorded = $WithReadTimeRecorded;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalClicks(): int
    {
        return $this->TotalClicks;
    }

    /**
     * @param int $TotalClicks
     * @return PostmarkOutboundOverviewStats
     */
    public function setTotalClicks(int $TotalClicks): PostmarkOutboundOverviewStats
    {
        $this->TotalClicks = $TotalClicks;
        return $this;
    }

    /**
     * @return int
     */
    public function getUniqueLinksClicked(): int
    {
        return $this->UniqueLinksClicked;
    }

    /**
     * @param int $UniqueLinksClicked
     * @return PostmarkOutboundOverviewStats
     */
    public function setUniqueLinksClicked(int $UniqueLinksClicked): PostmarkOutboundOverviewStats
    {
        $this->UniqueLinksClicked = $UniqueLinksClicked;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalTrackedLinksSent(): int
    {
        return $this->TotalTrackedLinksSent;
    }

    /**
     * @param int $TotalTrackedLinksSent
     * @return PostmarkOutboundOverviewStats
     */
    public function setTotalTrackedLinksSent(int $TotalTrackedLinksSent): PostmarkOutboundOverviewStats
    {
        $this->TotalTrackedLinksSent = $TotalTrackedLinksSent;
        return $this;
    }

    /**
     * @return int
     */
    public function getWithLinkTracking(): int
    {
        return $this->WithLinkTracking;
    }

    /**
     * @param int $WithLinkTracking
     * @return PostmarkOutboundOverviewStats
     */
    public function setWithLinkTracking(int $WithLinkTracking): PostmarkOutboundOverviewStats
    {
        $this->WithLinkTracking = $WithLinkTracking;
        return $this;
    }

    /**
     * @return int
     */
    public function getWithOpenTracking(): int
    {
        return $this->WithOpenTracking;
    }

    /**
     * @param int $WithOpenTracking
     * @return PostmarkOutboundOverviewStats
     */
    public function setWithOpenTracking(int $WithOpenTracking): PostmarkOutboundOverviewStats
    {
        $this->WithOpenTracking = $WithOpenTracking;
        return $this;
    }
}