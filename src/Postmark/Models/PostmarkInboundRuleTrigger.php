<?php

namespace Postmark\Models;

class PostmarkInboundRuleTrigger
{
    public int $ID;
    public string $Rule;

    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Rule = !empty($values['Rule']) ? $values['Rule'] : '';
    }

    public function getID(): int
    {
        return $this->ID;
    }

    public function setID(int $ID): PostmarkInboundRuleTrigger
    {
        $this->ID = $ID;

        return $this;
    }

    public function getRule(): string
    {
        return $this->Rule;
    }

    public function setRule(string $Rule): PostmarkInboundRuleTrigger
    {
        $this->Rule = $Rule;

        return $this;
    }
}
