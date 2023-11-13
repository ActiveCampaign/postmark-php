<?php

namespace Postmark\Models;

class PostmarkInboundRuleTrigger
{
    public int $ID;
    public string $Rule;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Rule = !empty($values['Rule']) ? $values['Rule'] : "";
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
     * @return PostmarkInboundRuleTrigger
     */
    public function setID(int $ID): PostmarkInboundRuleTrigger
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getRule(): string
    {
        return $this->Rule;
    }

    /**
     * @param string $Rule
     * @return PostmarkInboundRuleTrigger
     */
    public function setRule(string $Rule): PostmarkInboundRuleTrigger
    {
        $this->Rule = $Rule;
        return $this;
    }

}