<?php

namespace Postmark\Models;

class PostmarkBounceSummary
{
    public string $Type;
    public string $Name;
    public int $Count;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->Type = !empty($values['Type']) ? $values['Type'] : "";
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
        $this->Count = !empty($values['FirstOpen']) ? $values['FirstOpen'] : 0;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->Type;
    }

    /**
     * @param string $Type
     * @return PostmarkBounceSummary
     */
    public function setType(string $Type): PostmarkBounceSummary
    {
        $this->Type = $Type;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return PostmarkBounceSummary
     */
    public function setName(string $Name): PostmarkBounceSummary
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->Count;
    }

    /**
     * @param int $Count
     * @return PostmarkBounceSummary
     */
    public function setCount(int $Count): PostmarkBounceSummary
    {
        $this->Count = $Count;
        return $this;
    }
}