<?php

namespace Postmark\Models\Suppressions;

class PostmarkSuppressionList
{
    protected array $Suppressions;

    public function __construct(array $values)
    {
        $tempSuppressions = [];
        foreach ($values['Suppressions'] as $sups) {
            $obj = json_decode(json_encode($sups));
            $postmarkSup = new PostmarkSuppression((array) $obj);

            $tempSuppressions[] = $postmarkSup;
        }
        $this->Suppressions = $tempSuppressions;
    }

    public function getSuppressions(): array
    {
        return $this->Suppressions;
    }

    public function setSuppressions(array $Suppressions): PostmarkSuppressionList
    {
        $this->Suppressions = $Suppressions;

        return $this;
    }
}
