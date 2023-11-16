<?php

namespace Postmark\Models;

class PostmarkDeliveryStats
{
    public int $InactiveMails;
    public array $Bounces;

    public function __construct(array $values)
    {
        $this->InactiveMails = !empty($values['InactiveMails']) ? $values['InactiveMails'] : 0;
        $tempBounces = [];
        foreach ($values['Bounces'] as $bounce) {
            $obj = json_decode(json_encode($bounce));
            $postmarkBounce = new PostmarkBounceSummary((array) $obj);

            $tempBounces[] = $postmarkBounce;
        }
        $this->Bounces = $tempBounces;
    }

    public function getInactiveMails(): int
    {
        return $this->InactiveMails;
    }

    public function setInactiveMails(int $InactiveMails): PostmarkDeliveryStats
    {
        $this->InactiveMails = $InactiveMails;

        return $this;
    }

    public function getBounces(): array
    {
        return $this->Bounces;
    }

    public function setBounces(array $Bounces): PostmarkDeliveryStats
    {
        $this->Bounces = $Bounces;

        return $this;
    }
}
