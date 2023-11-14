<?php

namespace Postmark\Models;

class PostmarkBounceDump
{
    public string $Body;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->Body = !empty($values['Body']) ? $values['Body'] : "";
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->Body;
    }

    /**
     * @param string $Body
     * @return PostmarkBounceDump
     */
    public function setBody(string $Body): PostmarkBounceDump
    {
        $this->Body = $Body;
        return $this;
    }


}