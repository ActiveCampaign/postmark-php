<?php

namespace Postmark\Models;

class PostmarkBounce
{
    public int $ID;
    public string $Type;
    public string $Details;
    public string $Email;
    public string $BouncedAt;
    public bool $DumpAvailable;
    public bool $Inactive;
    public bool $CanActivate;
    public string $MessageID;
    public string $Description;
    public string $Tag;
    public string $Subject;
    public string $From;
    public int $ServerID;
    public string $Name;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Type = !empty($values['Type']) ? $values['Type'] : "";
        $this->Details = !empty($values['Details']) ? $values['Details'] : "";
        $this->Email = !empty($values['Email']) ? $values['Email'] : "";
        $this->BouncedAt = !empty($values['BouncedAt']) ? $values['BouncedAt'] : "";
        $this->DumpAvailable = !empty($values['DumpAvailable']) ? $values['DumpAvailable'] : false;
        $this->Inactive = !empty($values['Inactive']) ? $values['Inactive'] : false;
        $this->CanActivate = !empty($values['CanActivate']) ? $values['CanActivate'] : false;
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : "";
        $this->Description = !empty($values['Description']) ? $values['Description'] : "";
        $this->Tag = !empty($values['Tag']) ? $values['Tag'] : "";
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : "";
        $this->From = !empty($values['From']) ? $values['From'] : "";
        $this->ServerID = !empty($values['ServerID']) ? $values['ServerID'] : 0;
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
    }

    /**
     * @return int|mixed
     */
    public function getID(): mixed
    {
        return $this->ID;
    }

    /**
     * @param int|mixed $ID
     * @return PostmarkBounce
     */
    public function setID(mixed $ID): PostmarkBounce
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getType(): mixed
    {
        return $this->Type;
    }

    /**
     * @param mixed|string $Type
     * @return PostmarkBounce
     */
    public function setType(mixed $Type): PostmarkBounce
    {
        $this->Type = $Type;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDetails(): mixed
    {
        return $this->Details;
    }

    /**
     * @param mixed|string $Details
     * @return PostmarkBounce
     */
    public function setDetails(mixed $Details): PostmarkBounce
    {
        $this->Details = $Details;
        return $this;
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
     * @return PostmarkBounce
     */
    public function setEmail(mixed $Email): PostmarkBounce
    {
        $this->Email = $Email;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getBouncedAt(): mixed
    {
        return $this->BouncedAt;
    }

    /**
     * @param mixed|string $BouncedAt
     * @return PostmarkBounce
     */
    public function setBouncedAt(mixed $BouncedAt): PostmarkBounce
    {
        $this->BouncedAt = $BouncedAt;
        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getDumpAvailable(): mixed
    {
        return $this->DumpAvailable;
    }

    /**
     * @param bool|mixed $DumpAvailable
     * @return PostmarkBounce
     */
    public function setDumpAvailable(mixed $DumpAvailable): PostmarkBounce
    {
        $this->DumpAvailable = $DumpAvailable;
        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getInactive(): mixed
    {
        return $this->Inactive;
    }

    /**
     * @param bool|mixed $Inactive
     * @return PostmarkBounce
     */
    public function setInactive(mixed $Inactive): PostmarkBounce
    {
        $this->Inactive = $Inactive;
        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getCanActivate(): mixed
    {
        return $this->CanActivate;
    }

    /**
     * @param bool|mixed $CanActivate
     * @return PostmarkBounce
     */
    public function setCanActivate(mixed $CanActivate): PostmarkBounce
    {
        $this->CanActivate = $CanActivate;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMessageID(): mixed
    {
        return $this->MessageID;
    }

    /**
     * @param mixed|string $MessageID
     * @return PostmarkBounce
     */
    public function setMessageID(mixed $MessageID): PostmarkBounce
    {
        $this->MessageID = $MessageID;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDescription(): mixed
    {
        return $this->Description;
    }

    /**
     * @param mixed|string $Description
     * @return PostmarkBounce
     */
    public function setDescription(mixed $Description): PostmarkBounce
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getTag(): mixed
    {
        return $this->Tag;
    }

    /**
     * @param mixed|string $Tag
     * @return PostmarkBounce
     */
    public function setTag(mixed $Tag): PostmarkBounce
    {
        $this->Tag = $Tag;
        return $this;
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
     * @return PostmarkBounce
     */
    public function setSubject(mixed $Subject): PostmarkBounce
    {
        $this->Subject = $Subject;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getFrom(): mixed
    {
        return $this->From;
    }

    /**
     * @param mixed|string $From
     * @return PostmarkBounce
     */
    public function setFrom(mixed $From): PostmarkBounce
    {
        $this->From = $From;
        return $this;
    }

    /**
     * @return int|mixed
     */
    public function getServerID(): mixed
    {
        return $this->ServerID;
    }

    /**
     * @param int|mixed $ServerID
     * @return PostmarkBounce
     */
    public function setServerID(mixed $ServerID): PostmarkBounce
    {
        $this->ServerID = $ServerID;
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
     * @return PostmarkBounce
     */
    public function setName(mixed $Name): PostmarkBounce
    {
        $this->Name = $Name;
        return $this;
    }

}