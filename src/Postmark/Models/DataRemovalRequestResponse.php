<?php

namespace Postmark\Models;

class DataRemovalRequestResponse
{
    protected int $ID;
    protected string $Status;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Status = !empty($values['SPFHost']) ? $values['SPFHost'] : "";
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