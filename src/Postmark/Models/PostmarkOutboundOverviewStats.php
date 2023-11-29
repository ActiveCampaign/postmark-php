<?php

namespace Postmark\Models;

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

    public function getSent(): int
    {
        return $this->Sent;
    }

    public function setSent(int $Sent): PostmarkOutboundOverviewStats
    {
        $this->Sent = $Sent;

        return $this;
    }

    public function getBounced(): int
    {
        return $this->Bounced;
    }

    public function setBounced(int $Bounced): PostmarkOutboundOverviewStats
    {
        $this->Bounced = $Bounced;

        return $this;
    }

    public function getSMTPApiErrors(): int
    {
        return $this->SMTPApiErrors;
    }

    public function setSMTPApiErrors(int $SMTPApiErrors): PostmarkOutboundOverviewStats
    {
        $this->SMTPApiErrors = $SMTPApiErrors;

        return $this;
    }

    public function getBounceRate(): float
    {
        return $this->BounceRate;
    }

    public function setBounceRate(float $BounceRate): PostmarkOutboundOverviewStats
    {
        $this->BounceRate = $BounceRate;

        return $this;
    }

    public function getSpamComplaints(): int
    {
        return $this->SpamComplaints;
    }

    public function setSpamComplaints(int $SpamComplaints): PostmarkOutboundOverviewStats
    {
        $this->SpamComplaints = $SpamComplaints;

        return $this;
    }

    public function getSpamComplaintsRate(): float
    {
        return $this->SpamComplaintsRate;
    }

    public function setSpamComplaintsRate(float $SpamComplaintsRate): PostmarkOutboundOverviewStats
    {
        $this->SpamComplaintsRate = $SpamComplaintsRate;

        return $this;
    }

    public function getOpens(): int
    {
        return $this->Opens;
    }

    public function setOpens(int $Opens): PostmarkOutboundOverviewStats
    {
        $this->Opens = $Opens;

        return $this;
    }

    public function getUniqueOpens(): int
    {
        return $this->UniqueOpens;
    }

    public function setUniqueOpens(int $UniqueOpens): PostmarkOutboundOverviewStats
    {
        $this->UniqueOpens = $UniqueOpens;

        return $this;
    }

    public function getTracked(): int
    {
        return $this->Tracked;
    }

    public function setTracked(int $Tracked): PostmarkOutboundOverviewStats
    {
        $this->Tracked = $Tracked;

        return $this;
    }

    public function getWithClientRecorded(): int
    {
        return $this->WithClientRecorded;
    }

    public function setWithClientRecorded(int $WithClientRecorded): PostmarkOutboundOverviewStats
    {
        $this->WithClientRecorded = $WithClientRecorded;

        return $this;
    }

    public function getWithPlatformRecorded(): int
    {
        return $this->WithPlatformRecorded;
    }

    public function setWithPlatformRecorded(int $WithPlatformRecorded): PostmarkOutboundOverviewStats
    {
        $this->WithPlatformRecorded = $WithPlatformRecorded;

        return $this;
    }

    public function getWithReadTimeRecorded(): int
    {
        return $this->WithReadTimeRecorded;
    }

    public function setWithReadTimeRecorded(int $WithReadTimeRecorded): PostmarkOutboundOverviewStats
    {
        $this->WithReadTimeRecorded = $WithReadTimeRecorded;

        return $this;
    }

    public function getTotalClicks(): int
    {
        return $this->TotalClicks;
    }

    public function setTotalClicks(int $TotalClicks): PostmarkOutboundOverviewStats
    {
        $this->TotalClicks = $TotalClicks;

        return $this;
    }

    public function getUniqueLinksClicked(): int
    {
        return $this->UniqueLinksClicked;
    }

    public function setUniqueLinksClicked(int $UniqueLinksClicked): PostmarkOutboundOverviewStats
    {
        $this->UniqueLinksClicked = $UniqueLinksClicked;

        return $this;
    }

    public function getTotalTrackedLinksSent(): int
    {
        return $this->TotalTrackedLinksSent;
    }

    public function setTotalTrackedLinksSent(int $TotalTrackedLinksSent): PostmarkOutboundOverviewStats
    {
        $this->TotalTrackedLinksSent = $TotalTrackedLinksSent;

        return $this;
    }

    public function getWithLinkTracking(): int
    {
        return $this->WithLinkTracking;
    }

    public function setWithLinkTracking(int $WithLinkTracking): PostmarkOutboundOverviewStats
    {
        $this->WithLinkTracking = $WithLinkTracking;

        return $this;
    }

    public function getWithOpenTracking(): int
    {
        return $this->WithOpenTracking;
    }

    public function setWithOpenTracking(int $WithOpenTracking): PostmarkOutboundOverviewStats
    {
        $this->WithOpenTracking = $WithOpenTracking;

        return $this;
    }
}
