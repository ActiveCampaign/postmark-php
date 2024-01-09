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

    public function getID(): int
    {
        return $this->ID;
    }

    public function setID(int $ID): PostmarkBounce
    {
        $this->ID = $ID;

        return $this;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function setType(string $Type): PostmarkBounce
    {
        $this->Type = $Type;

        return $this;
    }

    public function getTypeCode(): int
    {
        return $this->TypeCode;
    }

    public function setTypeCode(int $TypeCode): PostmarkBounce
    {
        $this->TypeCode = $TypeCode;

        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): PostmarkBounce
    {
        $this->Name = $Name;

        return $this;
    }

    public function getTag(): string
    {
        return $this->Tag;
    }

    public function setTag(string $Tag): PostmarkBounce
    {
        $this->Tag = $Tag;

        return $this;
    }

    public function getMessageID(): string
    {
        return $this->MessageID;
    }

    public function setMessageID(string $MessageID): PostmarkBounce
    {
        $this->MessageID = $MessageID;

        return $this;
    }

    public function getServerID(): int
    {
        return $this->ServerID;
    }

    public function setServerID(int $ServerID): PostmarkBounce
    {
        $this->ServerID = $ServerID;

        return $this;
    }

    public function getMessageStream(): string
    {
        return $this->MessageStream;
    }

    public function setMessageStream(string $MessageStream): PostmarkBounce
    {
        $this->MessageStream = $MessageStream;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): PostmarkBounce
    {
        $this->Description = $Description;

        return $this;
    }

    public function getDetails(): string
    {
        return $this->Details;
    }

    public function setDetails(string $Details): PostmarkBounce
    {
        $this->Details = $Details;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): PostmarkBounce
    {
        $this->Email = $Email;

        return $this;
    }

    public function getFrom(): string
    {
        return $this->From;
    }

    public function setFrom(string $From): PostmarkBounce
    {
        $this->From = $From;

        return $this;
    }

    public function getBouncedAt(): string
    {
        return $this->BouncedAt;
    }

    public function setBouncedAt(string $BouncedAt): PostmarkBounce
    {
        $this->BouncedAt = $BouncedAt;

        return $this;
    }

    public function getDumpAvailable(): bool
    {
        return $this->DumpAvailable;
    }

    public function setDumpAvailable(bool $DumpAvailable): PostmarkBounce
    {
        $this->DumpAvailable = $DumpAvailable;

        return $this;
    }

    public function getInactive(): bool
    {
        return $this->Inactive;
    }

    public function setInactive(bool $Inactive): PostmarkBounce
    {
        $this->Inactive = $Inactive;

        return $this;
    }

    public function getCanActivate(): bool
    {
        return $this->CanActivate;
    }

    public function setCanActivate(bool $CanActivate): PostmarkBounce
    {
        $this->CanActivate = $CanActivate;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->Subject;
    }

    public function setSubject(string $Subject): PostmarkBounce
    {
        $this->Subject = $Subject;

        return $this;
    }

    public function getContent(): string
    {
        return $this->Content;
    }

    public function setContent(string $Content): PostmarkBounce
    {
        $this->Content = $Content;

        return $this;
    }
}
