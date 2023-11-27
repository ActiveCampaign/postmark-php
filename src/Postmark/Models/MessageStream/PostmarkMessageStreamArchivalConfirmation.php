<?php

namespace Postmark\Models\MessageStream;

class PostmarkMessageStreamArchivalConfirmation
{
    public string $ID;
    public int $ServerID;
    public string $ExpectedPurgeDate;

    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : '';
        $this->ServerID = !empty($values['ServerID']) ? $values['ServerID'] : 0;
        $this->ExpectedPurgeDate = !empty($values['ExpectedPurgeDate']) ? $values['ExpectedPurgeDate'] : '';
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): PostmarkMessageStreamArchivalConfirmation
    {
        $this->ID = $ID;

        return $this;
    }

    public function getServerID(): int
    {
        return $this->ServerID;
    }

    public function setServerID(int $ServerID): PostmarkMessageStreamArchivalConfirmation
    {
        $this->ServerID = $ServerID;

        return $this;
    }

    public function getExpectedPurgeDate(): string
    {
        return $this->ExpectedPurgeDate;
    }

    public function setExpectedPurgeDate(string $ExpectedPurgeDate): PostmarkMessageStreamArchivalConfirmation
    {
        $this->ExpectedPurgeDate = $ExpectedPurgeDate;

        return $this;
    }
}
