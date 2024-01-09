<?php

namespace Postmark\Models;

class PostmarkMessageDump
{
    public string $Body;

    public function __construct(string $body = '')
    {
        $this->Body = $body;
    }

    public function getBody(): string
    {
        return $this->Body;
    }

    public function setBody(string $Body): PostmarkMessageDump
    {
        $this->Body = $Body;

        return $this;
    }
}
