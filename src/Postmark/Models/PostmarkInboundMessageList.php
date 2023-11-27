<?php

namespace Postmark\Models;

class PostmarkInboundMessageList
{
    public int $TotalCount;
    public array $InboundMessages;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempInboundMessages = [];
        foreach ($values['InboundMessages'] as $message) {
            $obj = json_decode(json_encode($message));
            $postmarkMessage = new PostmarkInboundMessage((array) $obj);

            $tempInboundMessages[] = $postmarkMessage;
        }
        $this->InboundMessages = $tempInboundMessages;
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
     */
    public function setTotalCount(mixed $TotalCount): PostmarkInboundMessageList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getInboundMessages(): array
    {
        return $this->InboundMessages;
    }

    public function setInboundMessages(array $InboundMessages): PostmarkInboundMessageList
    {
        $this->InboundMessages = $InboundMessages;

        return $this;
    }
}
