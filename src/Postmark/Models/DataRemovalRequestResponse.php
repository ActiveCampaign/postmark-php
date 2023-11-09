<?php

namespace Postmark\Models;

class DataRemovalRequestResponse
{
    protected int $ID;
    protected string $Status;

    /**
     * @param int $ID
     * @param string $Status
     */
    public function __construct(int $ID, string $Status)
    {
        $this->ID = $ID;
        $this->Status = $Status;
    }

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     * @return DataRemovalRequestResponse
     */
    public function setID(int $ID): DataRemovalRequestResponse
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->Status;
    }

    /**
     * @param string $Status
     * @return DataRemovalRequestResponse
     */
    public function setStatus(string $Status): DataRemovalRequestResponse
    {
        $this->Status = $Status;
        return $this;
    }
}