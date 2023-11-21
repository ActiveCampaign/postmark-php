<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkInboundRuleTrigger;

class PostmarkInboundRuleTriggerList
{
    public int $TotalCount;
    public array $Rules;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempRules = array();
        foreach ($values['InboundRules'] as $rule) {
            $obj = json_decode(json_encode($rule));
            $postmarkServer = new PostmarkInboundRuleTrigger((array)$obj);

            $tempRules[] = $postmarkServer;
        }
        $this->Rules = $tempRules;
    }

    /**
     * @return int|mixed
     */
    public function getTotalCount(): mixed
    {
        return $this->TotalCount;
    }

    /**
     * @param int|mixed $TotalCount
     * @return PostmarkInboundRuleTriggerList
     */
    public function setTotalCount(mixed $TotalCount): PostmarkInboundRuleTriggerList
    {
        $this->TotalCount = $TotalCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->Rules;
    }

    /**
     * @param array $Rules
     * @return PostmarkInboundRuleTriggerList
     */
    public function setRules(array $Rules): PostmarkInboundRuleTriggerList
    {
        $this->Rules = $Rules;
        return $this;
    }
}