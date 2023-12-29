<?php

namespace Postmark\Models;

class PostmarkBounceActivation
{
    public string $Message;
    public PostmarkBounce $Bounce;

    public function __construct(array $values)
    {
        $this->Message = !empty($values['Message']) ? $values['Message'] : '';
        $this->Bounce = !empty($values['Bounce']) ? $values['Bounce'] : new PostmarkBounce([]);
    }

    /**
     * @return mixed|string
     */
    public function getMessage(): mixed
    {
        return $this->Message;
    }

    /**
     * @param mixed|string $Message
     */
    public function setMessage(mixed $Message): PostmarkBounceActivation
    {
        $this->Message = $Message;

        return $this;
    }

    /**
     * @return mixed|PostmarkBounce
     */
    public function getBounce(): mixed
    {
        return $this->Bounce;
    }

    /**
     * @param mixed|PostmarkBounce $Bounce
     */
    public function setBounce(mixed $Bounce): PostmarkBounceActivation
    {
        $this->Bounce = $Bounce;

        return $this;
    }
}
