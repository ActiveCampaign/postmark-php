<?php

namespace Postmark\Models;

class PostmarkMessage extends PostmarkMessageBase
{
    public string $Subject;
    public string $HtmlBody;
    public string $TextBody;

    public function __construct(array $values)
    {
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : '';
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : '';
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : '';
    }

    /**
     * @return mixed|string
     */
    public function getSubject(): mixed
    {
        return $this->Subject;
    }

    /**
     * @param mixed|string $Subject
     */
    public function setSubject(mixed $Subject): PostmarkMessage
    {
        $this->Subject = $Subject;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getHtmlBody(): mixed
    {
        return $this->HtmlBody;
    }

    /**
     * @param mixed|string $HtmlBody
     */
    public function setHtmlBody(mixed $HtmlBody): PostmarkMessage
    {
        $this->HtmlBody = $HtmlBody;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTextBody(): mixed
    {
        return $this->TextBody;
    }

    /**
     * @param mixed|string $TextBody
     */
    public function setTextBody(mixed $TextBody): PostmarkMessage
    {
        $this->TextBody = $TextBody;

        return $this;
    }
}
