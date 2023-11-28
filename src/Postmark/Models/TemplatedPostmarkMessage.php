<?php

namespace Postmark\Models;

class TemplatedPostmarkMessage extends PostmarkMessageBase
{
    public ?int $TemplateId;
    public ?string $TemplateAlias;
    public bool $InlineCss;
    public ?array $TemplateModel;

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->TemplateId = !empty($values['TemplateId']) ? $values['TemplateId'] : 0;
        $this->TemplateAlias = !empty($values['TemplateAlias']) ? $values['TemplateAlias'] : '';
        $this->InlineCss = !empty($values['InlineCss']) ? $values['InlineCss'] : true;
        $this->TemplateModel = !empty($values['TemplateModel']) ? $values['TemplateModel'] : [];

        parent::__construct($values);
    }

    /**
     * @return int|null
     */
    public function getTemplateId(): ?int
    {
        return $this->TemplateId;
    }

    /**
     * @param int|null $TemplateId
     * @return TemplatedPostmarkMessage
     */
    public function setTemplateId(?int $TemplateId): TemplatedPostmarkMessage
    {
        $this->TemplateId = $TemplateId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTemplateAlias(): ?string
    {
        return $this->TemplateAlias;
    }

    /**
     * @param string|null $TemplateAlias
     * @return TemplatedPostmarkMessage
     */
    public function setTemplateAlias(?string $TemplateAlias): TemplatedPostmarkMessage
    {
        $this->TemplateAlias = $TemplateAlias;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInlineCss(): bool
    {
        return $this->InlineCss;
    }

    /**
     * @param bool $InlineCss
     * @return TemplatedPostmarkMessage
     */
    public function setInlineCss(bool $InlineCss): TemplatedPostmarkMessage
    {
        $this->InlineCss = $InlineCss;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getTemplateModel(): ?array
    {
        return $this->TemplateModel;
    }

    /**
     * @param array|null $TemplateModel
     * @return TemplatedPostmarkMessage
     */
    public function setTemplateModel(?array $TemplateModel): TemplatedPostmarkMessage
    {
        $this->TemplateModel = $TemplateModel;
        return $this;
    }

}