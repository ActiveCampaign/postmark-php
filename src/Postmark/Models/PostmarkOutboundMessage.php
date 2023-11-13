<?php

namespace Postmark\Models;

class PostmarkOutboundMessage
{
    public string $Tag;
    public string $MessageID;
    public array $To;
    public array $Cc;
    public array $Bcc;
    public array $Recipients;
    public string $ReceivedAt;
    public string $From;
    public string $Subject;
    public array $Attachments;
    public string $Status;
    public array $Metadata;
    
}