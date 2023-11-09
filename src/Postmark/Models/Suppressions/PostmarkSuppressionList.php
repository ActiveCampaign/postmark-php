<?php

namespace Postmark\Models\Suppressions;

use Postmark\Models\Suppressions\PostmarkSuppression;

class PostmarkSuppressionList
{
    protected array $Suppressions;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $tempSuppressions = array();
        foreach ($values['Suppressions'] as $sups)
        {
            $obj = json_decode(json_encode($sups));
            $postmarkSup = new PostmarkSuppression((array)$obj);

            $tempSuppressions[] = $postmarkSup;
        }
        $this->Suppressions = $tempSuppressions;
    }

    /**
     * @return array
     */
    public function getSuppressions(): array
    {
        return $this->Suppressions;
    }

    /**
     * @param array $Suppressions
     * @return PostmarkSuppressionList
     */
    public function setSuppressions(array $Suppressions): PostmarkSuppressionList
    {
        $this->Suppressions = $Suppressions;
        return $this;
    }

}