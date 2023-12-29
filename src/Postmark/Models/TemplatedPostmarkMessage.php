<?php

namespace Postmark\Models;

class TemplatedPostmarkMessage extends PostmarkMessageBase
{
    public ?int $TemplateId;
    public ?string $TemplateAlias;
    public bool $InlineCss;
    public ?array $TemplateModel;

    public function __construct(array $values = [])
    {
        $this->TemplateId = !empty($values['TemplateId']) ? $values['TemplateId'] : 0;
        $this->TemplateAlias = !empty($values['TemplateAlias']) ? $values['TemplateAlias'] : '';
        $this->InlineCss = !empty($values['InlineCss']) ? $values['InlineCss'] : true;
        $this->TemplateModel = !empty($values['TemplateModel']) ? $values['TemplateModel'] : [];

        parent::__construct($values);
    }

    public function getTemplateId(): ?int
    {
        return $this->TemplateId;
    }

    public function setTemplateId(?int $TemplateId): TemplatedPostmarkMessage
    {
        $this->TemplateId = $TemplateId;

        return $this;
    }

    public function getTemplateAlias(): ?string
    {
        return $this->TemplateAlias;
    }

    public function setTemplateAlias(?string $TemplateAlias): TemplatedPostmarkMessage
    {
        $this->TemplateAlias = $TemplateAlias;

        return $this;
    }

    public function isInlineCss(): bool
    {
        return $this->InlineCss;
    }

    public function setInlineCss(bool $InlineCss): TemplatedPostmarkMessage
    {
        $this->InlineCss = $InlineCss;

        return $this;
    }

    public function getTemplateModel(): ?array
    {
        return $this->TemplateModel;
    }

    public function setTemplateModel(?array $TemplateModel): TemplatedPostmarkMessage
    {
        $this->TemplateModel = $TemplateModel;

        return $this;
    }
}
