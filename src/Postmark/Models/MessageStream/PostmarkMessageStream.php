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

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : "";
        $this->ServerID = !empty($values['ServerID']) ? $values['ServerID'] : 0;
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
        $this->Description = !empty($values['Description']) ? $values['Description'] : "";
        $this->MessageStreamType = !empty($values['MessageStreamType']) ? $values['MessageStreamType'] : "";
        $this->CreatedAt = !empty($values['CreatedAt']) ? $values['CreatedAt'] : "";
        $this->UpdatedAt = !empty($values['UpdatedAt']) ? $values['UpdatedAt'] : null;
        $this->ArchivedAt = !empty($values['ArchivedAt']) ? $values['ArchivedAt'] : null;
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->ID;
    }

    /**
     * @param string $ID
     * @return PostmarkMessageStream
     */
    public function setID(string $ID): PostmarkMessageStream
    {
        $this->ID = $ID;
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
     * @return PostmarkMessageStream
     */
    public function setServerID(int $ServerID): PostmarkMessageStream
    {
        $this->ServerID = $ServerID;
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
     * @return PostmarkMessageStream
     */
    public function setName(string $Name): PostmarkMessageStream
    {
        $this->Name = $Name;
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
     * @return PostmarkMessageStream
     */
    public function setDescription(string $Description): PostmarkMessageStream
    {
        $this->Description = $Description;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageStreamType(): string
    {
        return $this->MessageStreamType;
    }

    /**
     * @param string $MessageStreamType
     * @return PostmarkMessageStream
     */
    public function setMessageStreamType(string $MessageStreamType): PostmarkMessageStream
    {
        $this->MessageStreamType = $MessageStreamType;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->CreatedAt;
    }

    /**
     * @param string $CreatedAt
     * @return PostmarkMessageStream
     */
    public function setCreatedAt(string $CreatedAt): PostmarkMessageStream
    {
        $this->CreatedAt = $CreatedAt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->UpdatedAt;
    }

    /**
     * @param string|null $UpdatedAt
     * @return PostmarkMessageStream
     */
    public function setUpdatedAt(?string $UpdatedAt): PostmarkMessageStream
    {
        $this->UpdatedAt = $UpdatedAt;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getArchivedAt(): ?string
    {
        return $this->ArchivedAt;
    }

    /**
     * @param string|null $ArchivedAt
     * @return PostmarkMessageStream
     */
    public function setArchivedAt(?string $ArchivedAt): PostmarkMessageStream
    {
        $this->ArchivedAt = $ArchivedAt;
        return $this;
    }
}