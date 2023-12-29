<?php

namespace Postmark\Models;

class PostmarkSenderSignatureList
{
    public int $TotalCount;
    public array $SenderSignatures;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempSigs = [];
        foreach ($values['SenderSignatures'] as $open) {
            $obj = json_decode(json_encode($open));
            $postmarkSenderSig = new PostmarkSenderSignature((array) $obj);

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
     */
    public function setTotalCount(mixed $TotalCount): PostmarkSenderSignatureList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getSenderSignatures(): array
    {
        return $this->SenderSignatures;
    }

    public function setSenderSignatures(array $SenderSignatures): PostmarkSenderSignatureList
    {
        $this->SenderSignatures = $SenderSignatures;

        return $this;
    }
}
