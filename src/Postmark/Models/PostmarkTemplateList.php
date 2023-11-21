<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkTemplate;

class PostmarkTemplateList
{
    public int $TotalCount;
    public array $Templates;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempTemplates = array();
        foreach ($values['Templates'] as $template)
        {
            $obj = json_decode(json_encode($template));
            $postmarkTemplate = new PostmarkTemplate((array)$obj);

            $tempTemplates[] = $postmarkTemplate;
        }
        $this->Templates = $tempTemplates;
    }

    /**
     * @return int|mixed
     */
    public function getTotalCount(): mixed
    {
        return $this->TotalCount;
    }

    /**
     * @param int|mixed $TotalCount
     * @return PostmarkTemplateList
     */
    public function setTotalCount(mixed $TotalCount): PostmarkTemplateList
    {
        $this->TotalCount = $TotalCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemplates(): array
    {
        return $this->Templates;
    }

    /**
     * @param array $Templates
     * @return PostmarkTemplateList
     */
    public function setTemplates(array $Templates): PostmarkTemplateList
    {
        $this->Templates = $Templates;
        return $this;
    }
}