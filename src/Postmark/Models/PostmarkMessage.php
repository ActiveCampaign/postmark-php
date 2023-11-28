<?php

namespace Postmark\Models;

class PostmarkMessage extends PostmarkMessageBase
{
    public string $Subject;
    public string $HtmlBody;
    public string $TextBody;

    public function __construct(array $values = [])
    {
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : '';
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : '';
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : '';

        parent::__construct($values);
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->Subject;
    }

    /**
     * @param string $Subject
     * @return PostmarkMessage
     */
    public function setSubject(string $Subject): PostmarkMessage
    {
        $this->Subject = $Subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtmlBody(): string
    {
        return $this->HtmlBody;
    }

    /**
     * @param string $HtmlBody
     * @return PostmarkMessage
     */
    public function setHtmlBody(string $HtmlBody): PostmarkMessage
    {
        $this->HtmlBody = $HtmlBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getTextBody(): string
    {
        return $this->TextBody;
    }

    /**
     * @param string $TextBody
     * @return PostmarkMessage
     */
    public function setTextBody(string $TextBody): PostmarkMessage
    {
        $this->TextBody = $TextBody;

        return $this;
    }
}
