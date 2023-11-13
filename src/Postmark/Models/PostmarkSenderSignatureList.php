<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkSenderSignature;

class PostmarkSenderSignatureList
{
    public int $TotalCount;
    public array $SenderSignatures;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempSigs = array();
        foreach ($values['SenderSignatures'] as $open) {
            $obj = json_decode(json_encode($open));
            $postmarkSenderSig = new PostmarkSenderSignature((array)$obj);

            $tempSigs[] = $postmarkSenderSig;
        }
        $this->SenderSignatures = $tempSigs;
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
     * @return PostmarkSenderSignatureList
     */
    public function setTotalCount(mixed $TotalCount): PostmarkSenderSignatureList
    {
        $this->TotalCount = $TotalCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getSenderSignatures(): array
    {
        return $this->SenderSignatures;
    }

    /**
     * @param array $SenderSignatures
     * @return PostmarkSenderSignatureList
     */
    public function setSenderSignatures(array $SenderSignatures): PostmarkSenderSignatureList
    {
        $this->SenderSignatures = $SenderSignatures;
        return $this;
    }

}
