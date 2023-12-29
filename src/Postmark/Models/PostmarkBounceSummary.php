<?php

namespace Postmark\Models;

class PostmarkBounceSummary
{
    public string $Type;
    public string $Name;
    public int $Count;

    public function __construct(array $values)
    {
        $this->Type = !empty($values['Type']) ? $values['Type'] : '';
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->Count = !empty($values['FirstOpen']) ? $values['FirstOpen'] : 0;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $Type): PostmarkBounceSummary
    {
        $this->Type = $Type;

        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): PostmarkBounceSummary
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCount(): int
    {
        return $this->Count;
    }

    public function setCount(int $Count): PostmarkBounceSummary
    {
        $this->Count = $Count;

        return $this;
    }
}
