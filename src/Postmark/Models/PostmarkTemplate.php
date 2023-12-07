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
        $this->Alias = !empty($values['Alias']) ? $values['Alias'] : '';
        $this->Subject = !empty($values['Subject']) ? $values['Subject'] : '';
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->HtmlBody = !empty($values['HtmlBody']) ? $values['HtmlBody'] : '';
        $this->TextBody = !empty($values['TextBody']) ? $values['TextBody'] : '';
        $this->AssociatedServerId = !empty($values['AssociatedServerId']) ? $values['AssociatedServerId'] : 0;
        $this->Active = !empty($values['Active']) ? $values['Active'] : false;
        $this->TemplateType = !empty($values['TemplateType']) ? $values['TemplateType'] : 'Standard';
        $this->LayoutTemplate = !empty($values['LayoutTemplate']) ? $values['LayoutTemplate'] : null;
    }

    public function getTemplateId(): int
    {
        return $this->TemplateId;
    }

    public function setTemplateId(int $id): PostmarkTemplate
    {
        $this->TemplateId = $id;

        return $this;
    }

    public function getAlias(): string
    {
        return $this->Alias;
    }

    public function setAlias(string $Alias): PostmarkTemplate
    {
        $this->Alias = $Alias;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->Subject;
    }

    public function setSubject(string $Subject): PostmarkTemplate
    {
        $this->Subject = $Subject;

        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): PostmarkTemplate
    {
        $this->Name = $Name;

        return $this;
    }

    public function getHtmlBody(): string
    {
        return $this->HtmlBody;
    }

    public function setHtmlBody(string $HtmlBody): PostmarkTemplate
    {
        $this->HtmlBody = $HtmlBody;

        return $this;
    }

    public function getTextBody(): string
    {
        return $this->TextBody;
    }

    public function setTextBody(string $TextBody): PostmarkTemplate
    {
        $this->TextBody = $TextBody;

        return $this;
    }

    public function getAssociatedServerId(): int
    {
        return $this->AssociatedServerId;
    }

    public function setAssociatedServerId(int $AssociatedServerId): PostmarkTemplate
    {
        $this->AssociatedServerId = $AssociatedServerId;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->Active;
    }

    public function setActive(bool $Active): PostmarkTemplate
    {
        $this->Active = $Active;

        return $this;
    }

    public function getTemplateType(): string
    {
        return $this->TemplateType;
    }

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

    public function setLayoutTemplate(string $LayoutTemplate): PostmarkTemplate
    {
        $this->LayoutTemplate = $LayoutTemplate;

        return $this;
    }
}
