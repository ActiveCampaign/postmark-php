<?php

namespace Postmark\Models\Suppressions;

class PostmarkSuppressionResultList
{
    protected array $Suppressions;

    public function __construct(array $values)
    {
        $tempSuppressions = [];
        foreach ($values['Suppressions'] as $sups) {
            $obj = json_decode(json_encode($sups));
            $postmarkSup = new PostmarkSuppressionRequestResult((array) $obj);

            $tempSuppressions[] = $postmarkSup;
        }
        $this->Suppressions = $tempSuppressions;
    }

    public function getSuppressions(): array
    {
        return $this->Suppressions;
    }

    public function setSuppressions(array $Suppressions): PostmarkSuppressionResultList
    {
        $this->Suppressions = $Suppressions;

        return $this;
    }
}
