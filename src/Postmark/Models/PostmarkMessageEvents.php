<?php

namespace Postmark\Models;

class PostmarkMessageEvents
{
    public array $MessageEvents;

    public function __construct(array $values = [])
    {
        $tempMessageEvents = [];
        foreach ($values['MessageEvents'] as $messageEvent) {
            $obj = json_decode(json_encode($messageEvent));
            $postmarkMessage = new PostmarkMessageEvent((array)$obj);

            $tempMessageEvents[] = $postmarkMessage;
        }
        $this->MessageEvents = $tempMessageEvents;
    }

    public function getMessageEvents(): array
    {
        return $this->MessageEvents;
    }

    public function setMessageEvents(array $MessageEvents): PostmarkMessageEvents
    {
        $this->MessageEvents = $MessageEvents;

        return $this;
    }
}