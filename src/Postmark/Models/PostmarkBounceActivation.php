<?php

namespace Postmark\Models;

class PostmarkBounceActivation
{
    public string $Message;
    public PostmarkBounce $Bounce;

    public function __construct(array $values)
    {
        $this->Message = !empty($values['Message']) ? $values['Message'] : '';
        $this->setBounce(!empty($values['Bounce']) ? $values['Bounce'] : []);
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

    public function setBounce(array $Bounce): PostmarkBounceActivation
    {
        $this->Bounce = new PostmarkBounce($Bounce);

        return $this;
    }
}
