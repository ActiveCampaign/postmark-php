<?php

namespace Postmark\Models;

class PostmarkBounce
{
    public int $ID;
    public string $Type;
    public int $TypeCode;
    public string $Name;
    public string $Tag;
    public string $MessageID;
    public int $ServerID;
    public string $MessageStream;
    public string $Description;
    public string $Details;
    public string $Email;
    public string $From;
    public string $BouncedAt;
    public bool $DumpAvailable;
    public bool $Inactive;
    public bool $CanActivate;
    public string $Subject;
    public string $Content;

    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Type = !empty($values['Type']) ? $values['Type'] : 0;
        $this->TypeCode = !empty($values['TypeCode']) ? $values['TypeCode'] : '';
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->Tag = !empty($values['Tag']) ? $values['Tag'] : '';
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : '';
        $this->ServerID = !empty($values['ServerID']) ? $values['ServerID'] : 0;
        $this->MessageStream = !empty($values['MessageStream']) ? $values['MessageStream'] : '';
        $this->Description = !empty($values['Description']) ? $values['Description'] : '';
        $this->Details = !empty($values['Details']) ? $values['Details'] : '';
        $this->Email = !empty($values['Email']) ? $values['Email'] : '';
        $this->From = !empty($values['From']) ? $values['From'] : '';
        $this->BouncedAt = !empty($values['BouncedAt']) ? $values['BouncedAt'] : '';
        $this->DumpAvailable = !empty($values['DumpAvailable']) ? $values['DumpAvailable'] : false;
        $this->Inactive = !empty($values['Inactive']) ? $values['Inactive'] : false;
        $this->CanActivate = !empty($values['CanActivate']) ? $values['CanActivate'] : false;
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : '';
        $this->Content = !empty($values['Content']) ? $values['Content'] : '';
    }

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     * @return PostmarkBounce
     */
    public function setID(int $ID): PostmarkBounce
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->Type;
    }

    /**
     * @param string $Type
     * @return PostmarkBounce
     */
    public function setType(string $Type): PostmarkBounce
    {
        $this->Type = $Type;
        return $this;
    }

    /**
     * @return int
     */
    public function getTypeCode(): int
    {
        return $this->TypeCode;
    }

    /**
     * @param int $TypeCode
     * @return PostmarkBounce
     */
    public function setTypeCode(int $TypeCode): PostmarkBounce
    {
        $this->TypeCode = $TypeCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return PostmarkBounce
     */
    public function setName(string $Name): PostmarkBounce
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->Tag;
    }

    /**
     * @param string $Tag
     * @return PostmarkBounce
     */
    public function setTag(string $Tag): PostmarkBounce
    {
        $this->Tag = $Tag;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageID(): string
    {
        return $this->MessageID;
    }

    /**
     * @param string $MessageID
     * @return PostmarkBounce
     */
    public function setMessageID(string $MessageID): PostmarkBounce
    {
        $this->MessageID = $MessageID;
        return $this;
    }

    /**
     * @return int
     */
    public function getServerID(): int
    {
        return $this->ServerID;
    }

    /**
     * @param int $ServerID
     * @return PostmarkBounce
     */
    public function setServerID(int $ServerID): PostmarkBounce
    {
        $this->ServerID = $ServerID;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageStream(): string
    {
        return $this->MessageStream;
    }

    /**
     * @param string $MessageStream
     * @return PostmarkBounce
     */
    public function setMessageStream(string $MessageStream): PostmarkBounce
    {
        $this->MessageStream = $MessageStream;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->Description;
    }

    /**
     * @param string $Description
     * @return PostmarkBounce
     */
    public function setDescription(string $Description): PostmarkBounce
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->Details;
    }

    /**
     * @param string $Details
     * @return PostmarkBounce
     */
    public function setDetails(string $Details): PostmarkBounce
    {
        $this->Details = $Details;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->Email;
    }

    /**
     * @param string $Email
     * @return PostmarkBounce
     */
    public function setEmail(string $Email): PostmarkBounce
    {
        $this->Email = $Email;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->From;
    }

    /**
     * @param string $From
     * @return PostmarkBounce
     */
    public function setFrom(string $From): PostmarkBounce
    {
        $this->From = $From;
        return $this;
    }

    /**
     * @return string
     */
    public function getBouncedAt(): string
    {
        return $this->BouncedAt;
    }

    /**
     * @param string $BouncedAt
     * @return PostmarkBounce
     */
    public function setBouncedAt(string $BouncedAt): PostmarkBounce
    {
        $this->BouncedAt = $BouncedAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDumpAvailable(): bool
    {
        return $this->DumpAvailable;
    }

    /**
     * @param bool $DumpAvailable
     * @return PostmarkBounce
     */
    public function setDumpAvailable(bool $DumpAvailable): PostmarkBounce
    {
        $this->DumpAvailable = $DumpAvailable;
        return $this;
    }

    /**
     * @return bool
     */
    public function getInactive(): bool
    {
        return $this->Inactive;
    }

    /**
     * @param bool $Inactive
     * @return PostmarkBounce
     */
    public function setInactive(bool $Inactive): PostmarkBounce
    {
        $this->Inactive = $Inactive;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCanActivate(): bool
    {
        return $this->CanActivate;
    }

    /**
     * @param bool $CanActivate
     * @return PostmarkBounce
     */
    public function setCanActivate(bool $CanActivate): PostmarkBounce
    {
        $this->CanActivate = $CanActivate;
        return $this;
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
     * @return PostmarkBounce
     */
    public function setSubject(string $Subject): PostmarkBounce
    {
        $this->Subject = $Subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->Content;
    }

    /**
     * @param string $Content
     * @return PostmarkBounce
     */
    public function setContent(string $Content): PostmarkBounce
    {
        $this->Content = $Content;
        return $this;
    }

}
