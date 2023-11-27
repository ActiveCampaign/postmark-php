<?php

namespace Postmark\Models\Templates;

use Postmark\Models\PostmarkResponse;

final class SendEmailWithTemplateResponse extends PostmarkResponse
{
    private string $Recipient;
    private string $SubmittedAt;
    private string $MessageID;

    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->Recipient = !empty($values['To']) ? $values['To'] : '';
        $this->SubmittedAt = !empty($values['SubmittedAt']) ? $values['SubmittedAt'] : '';
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : '';
    }

    public function getRecipient(): string
    {
        return $this->Recipient;
    }

    public function getSubmittedAt(): string
    {
        return $this->SubmittedAt;
    }

    public function getMessageID(): string
    {
        return $this->MessageID;
    }
}
