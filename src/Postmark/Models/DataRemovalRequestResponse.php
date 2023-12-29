<?php

namespace Postmark\Models;

class DataRemovalRequestResponse
{
    protected int $ID;
    protected string $Status;

    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Status = !empty($values['Status']) ? $values['Status'] : '';
    }

    public function getID(): int
    {
        return $this->ID;
    }

    public function setID(int $ID): DataRemovalRequestResponse
    {
        $this->ID = $ID;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->Status;
    }

    public function setStatus(string $Status): DataRemovalRequestResponse
    {
        $this->Status = $Status;

        return $this;
    }
}
