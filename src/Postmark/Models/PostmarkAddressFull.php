<?php

namespace Postmark\Models;

class PostmarkAddressFull
{
    public string $Email;
    public string $Name;
    public string $MailboxHash;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->Email = !empty($values['Email']) ? $values['Email'] : "";
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
        $this->MailboxHash = !empty($values['MailboxHash']) ? $values['MailboxHash'] : "";
    }

    /**
     * @return mixed|string
     */
    public function getEmail(): mixed
    {
        return $this->Email;
    }

    /**
     * @param mixed|string $Email
     * @return PostmarkAddressFull
     */
    public function setEmail(mixed $Email): PostmarkAddressFull
    {
        $this->Email = $Email;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getName(): mixed
    {
        return $this->Name;
    }

    /**
     * @param mixed|string $Name
     * @return PostmarkAddressFull
     */
    public function setName(mixed $Name): PostmarkAddressFull
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMailboxHash(): mixed
    {
        return $this->MailboxHash;
    }

    /**
     * @param mixed|string $MailboxHash
     * @return PostmarkAddressFull
     */
    public function setMailboxHash(mixed $MailboxHash): PostmarkAddressFull
    {
        $this->MailboxHash = $MailboxHash;
        return $this;
    }

}