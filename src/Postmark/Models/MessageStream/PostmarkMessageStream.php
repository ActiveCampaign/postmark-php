<?php

namespace Postmark\Models\MessageStream;

class PostmarkMessageStream
{
    public string $ID;
    public int $ServerID;
    public string $Name;
    public string $Description;
    public string $MessageStreamType;
    public string $CreatedAt;
    public ?string $UpdatedAt;
    public ?string $ArchivedAt;

    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : '';
        $this->ServerID = !empty($values['ServerID']) ? $values['ServerID'] : 0;
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->Description = !empty($values['Description']) ? $values['Description'] : '';
        $this->MessageStreamType = !empty($values['MessageStreamType']) ? $values['MessageStreamType'] : '';
        $this->CreatedAt = !empty($values['CreatedAt']) ? $values['CreatedAt'] : '';
        $this->UpdatedAt = !empty($values['UpdatedAt']) ? $values['UpdatedAt'] : null;
        $this->ArchivedAt = !empty($values['ArchivedAt']) ? $values['ArchivedAt'] : null;
    }

    public function getID(): string
    {
        return $this->ID;
    }

    public function setID(string $ID): PostmarkMessageStream
    {
        $this->ID = $ID;

        return $this;
    }

    public function getServerID(): int
    {
        return $this->ServerID;
    }

    public function setServerID(int $ServerID): PostmarkMessageStream
    {
        $this->ServerID = $ServerID;

        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): PostmarkMessageStream
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): PostmarkMessageStream
    {
        $this->Description = $Description;

        return $this;
    }

    public function getMessageStreamType(): string
    {
        return $this->MessageStreamType;
    }

    public function setMessageStreamType(string $MessageStreamType): PostmarkMessageStream
    {
        $this->MessageStreamType = $MessageStreamType;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(string $CreatedAt): PostmarkMessageStream
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?string $UpdatedAt): PostmarkMessageStream
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getArchivedAt(): ?string
    {
        return $this->ArchivedAt;
    }

    public function setArchivedAt(?string $ArchivedAt): PostmarkMessageStream
    {
        $this->ArchivedAt = $ArchivedAt;

        return $this;
    }
}
