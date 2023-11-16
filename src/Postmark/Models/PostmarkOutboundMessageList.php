<?php

namespace Postmark\Models;

class PostmarkOutboundMessageList
{
    public int $TotalCount;
    public array $Messages;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempMessages = [];
        foreach ($values['Messages'] as $message) {
            $obj = json_decode(json_encode($message));
            $postmarkMessage = new PostmarkOutboundMessage((array) $obj);

            $tempMessages[] = $postmarkMessage;
        }
        $this->Messages = $tempMessages;
    }

    /**
     * @return int|mixed
     */
    public function getTotalCount(): mixed
    {
        return $this->TotalCount;
    }

    /**
     * @param int $TotalCount
     */
    public function setTotalCount(mixed $TotalCount): PostmarkOutboundMessageList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getMessages(): array
    {
        return $this->Messages;
    }

    public function setMessages(array $Messages): PostmarkOutboundMessageList
    {
        $this->Messages = $Messages;

        return $this;
    }
}
