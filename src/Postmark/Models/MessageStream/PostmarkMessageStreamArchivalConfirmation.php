<?php

namespace Postmark\Models\MessageStream;

class PostmarkMessageStreamArchivalConfirmation
{
    public string $ID;
    public int $ServerID;
    public string $ExpectedPurgeDate;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : "";
        $this->ServerID = !empty($values['ServerID']) ? $values['ServerID'] : 0;
        $this->ExpectedPurgeDate = !empty($values['ExpectedPurgeDate']) ? $values['ExpectedPurgeDate'] : "";
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return PostmarkMessageStreamArchivalConfirmation
     */
    public function setID(string $ID): PostmarkMessageStreamArchivalConfirmation
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return int
     */
    public function getServerID(): int
    {
        return $this->ServerID;
    }

    /**
     * @param int $ServerID
     * @return PostmarkMessageStreamArchivalConfirmation
     */
    public function setServerID(int $ServerID): PostmarkMessageStreamArchivalConfirmation
    {
        $this->ServerID = $ServerID;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpectedPurgeDate(): string
    {
        return $this->ExpectedPurgeDate;
    }

    /**
     * @param string $ExpectedPurgeDate
     * @return PostmarkMessageStreamArchivalConfirmation
     */
    public function setExpectedPurgeDate(string $ExpectedPurgeDate): PostmarkMessageStreamArchivalConfirmation
    {
        $this->ExpectedPurgeDate = $ExpectedPurgeDate;
        return $this;
    }
}