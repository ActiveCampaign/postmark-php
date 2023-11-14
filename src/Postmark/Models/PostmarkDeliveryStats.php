<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkBounceSummary;

class PostmarkDeliveryStats
{
    public int $InactiveMails;
    public array $Bounces;

    public function __construct(array $values)
    {
        $this->InactiveMails = !empty($values['InactiveMails']) ? $values['InactiveMails'] : 0;
        $tempBounces = array();
        foreach ($values['Bounces'] as $bounce) {
            $obj = json_decode(json_encode($bounce));
            $postmarkBounce = new PostmarkBounceSummary((array)$obj);

            $tempBounces[] = $postmarkBounce;
        }
        $this->Bounces = $tempBounces;
    }

    /**
     * @return int
     */
    public function getInactiveMails(): int
    {
        return $this->InactiveMails;
    }

    /**
     * @param int $InactiveMails
     * @return PostmarkDeliveryStats
     */
    public function setInactiveMails(int $InactiveMails): PostmarkDeliveryStats
    {
        $this->InactiveMails = $InactiveMails;
        return $this;
    }

    /**
     * @return array
     */
    public function getBounces(): array
    {
        return $this->Bounces;
    }

    /**
     * @param array $Bounces
     * @return PostmarkDeliveryStats
     */
    public function setBounces(array $Bounces): PostmarkDeliveryStats
    {
        $this->Bounces = $Bounces;
        return $this;
    }

}