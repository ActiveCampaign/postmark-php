<?php

namespace Postmark\Models;

class PostmarkTemplate
{
    protected int $TemplateId;
    protected string $Alias;
    protected string $Subject;
    protected string $Name;
    protected string $HtmlBody;
    protected string $TextBody;
    protected int $AssociatedServerId;
    protected bool $Active;
    protected string $TemplateType;
    protected ?string $LayoutTemplate;

    public function __construct(array $values)
    {
        $this->TemplateId = !empty($values['TemplateId']) ? $values['TemplateId'] : 0;
        $this->Alias = !empty($values['Alias']) ? $values['Alias'] : "";
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : "";
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : "";
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : "";
        $this->AssociatedServerId = !empty($values['AssociatedServerId']) ? $values['AssociatedServerId'] : 0;
        $this->Active = !empty($values['Active']) ? $values['Active'] : false;
        $this->TemplateType = !empty($values['TemplateType']) ? $values['TemplateType'] : "Standard";
        $this->LayoutTemplate = !empty($values['LayoutTemplate']) ? $values['LayoutTemplate'] : null;
    }

    /**
     * @return int
     */
    public function getTemplateId(): int
    {
        return $this->TemplateId;
    }

    /**
     * @param int $id
     * @return PostmarkTemplate
     */
    public function setTemplateId(int $id): PostmarkTemplate
    {
        $this->TemplateId = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->Alias;
    }

    /**
     * @param string $Alias
     * @return PostmarkTemplate
     */
    public function setAlias(string $Alias): PostmarkTemplate
    {
        $this->Alias = $Alias;
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
     * @return PostmarkTemplate
     */
    public function setSubject(string $Subject): PostmarkTemplate
    {
        $this->Subject = $Subject;
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
     * @return PostmarkTemplate
     */
    public function setName(string $Name): PostmarkTemplate
    {
        $this->Name = $Name;
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
     * @return PostmarkTemplate
     */
    public function setHtmlBody(string $HtmlBody): PostmarkTemplate
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
     * @return PostmarkTemplate
     */
    public function setTextBody(string $TextBody): PostmarkTemplate
    {
        $this->TextBody = $TextBody;
        return $this;
    }

    /**
     * @return int
     */
    public function getAssociatedServerId(): int
    {
        return $this->AssociatedServerId;
    }

    /**
     * @param int $AssociatedServerId
     * @return PostmarkTemplate
     */
    public function setAssociatedServerId(int $AssociatedServerId): PostmarkTemplate
    {
        $this->AssociatedServerId = $AssociatedServerId;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->Active;
    }

    /**
     * @param bool $Active
     * @return PostmarkTemplate
     */
    public function setActive(bool $Active): PostmarkTemplate
    {
        $this->Active = $Active;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateType(): string
    {
        return $this->TemplateType;
    }

    /**
     * @param string $TemplateType
     * @return PostmarkTemplate
     */
    public function setTemplateType(string $TemplateType): PostmarkTemplate
    {
        $this->TemplateType = $TemplateType;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getLayoutTemplate(): ?string
    {
        return $this->LayoutTemplate;
    }

    /**
     * @param string $LayoutTemplate
     * @return PostmarkTemplate
     */
    public function setLayoutTemplate(string $LayoutTemplate): PostmarkTemplate
    {
        $this->LayoutTemplate = $LayoutTemplate;
        return $this;
    }
}