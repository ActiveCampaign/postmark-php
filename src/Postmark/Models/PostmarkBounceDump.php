<?php

namespace Postmark\Models;

class PostmarkBounceDump
{
    public string $Body;

    public function __construct(array $values)
    {
        $this->Body = !empty($values['Body']) ? $values['Body'] : '';
    }

    public function getBody(): string
    {
        return $this->Body;
    }

    public function setBody(string $Body): PostmarkBounceDump
    {
        $this->Body = $Body;

        return $this;
    }
}
