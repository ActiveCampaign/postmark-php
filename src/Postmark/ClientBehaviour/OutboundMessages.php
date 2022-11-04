<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;
use Postmark\PostmarkClient;

use function is_string;
use function sprintf;

/**
 * @internal \Postmark
 *
 * @see PostmarkClient
 *
 * @psalm-import-type MetaData from PostmarkClient
 */
trait OutboundMessages
{
    /**
     * Search messages that have been sent using this Server.
     *
     * @param int           $count         How many messages to retrieve at once (defaults to 100)
     * @param int           $offset        How many messages to skip when 'paging' through the massages (defaults to 0)
     * @param string|null   $recipient     Filter by recipient.
     * @param string|null   $fromEmail     Filter by sender email address.
     * @param string|null   $tag           Filter by tag.
     * @param string|null   $subject       Filter by subject.
     * @param string|null   $status        The current status for the outbound messages to return defaults to 'sent'
     * @param string|null   $fromdate      Filter to messages on or after YYYY-MM-DD
     * @param string|null   $todate        Filter to messages on or before YYYY-MM-DD
     * @param MetaData|null $metadata      An associative array of key-values that must all match values included in
     *                                     the metadata of matching sent messages.
     * @param string        $messagestream Filter by Message Stream ID. If null, the default "outbound" transactional
     *                                     stream will be used.
     */
    public function getOutboundMessages(
        int $count = 100,
        int $offset = 0,
        string|null $recipient = null,
        string|null $fromEmail = null,
        string|null $tag = null,
        string|null $subject = null,
        string|null $status = null,
        string|null $fromdate = null,
        string|null $todate = null,
        array|null $metadata = null,
        string $messagestream = 'outbound',
    ): DynamicResponseModel {
        $query = [];
        $query['recipient'] = $recipient;
        $query['fromemail'] = $fromEmail;
        $query['tag'] = $tag;
        $query['subject'] = $subject;
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['status'] = $status;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        if ($metadata !== null) {
            foreach ($metadata as $name => $value) {
                /** @psalm-suppress DocblockTypeContradiction */
                if (! is_string($name) || empty($value)) {
                    continue;
                }

                $key = sprintf('metadata_%s', $name);
                $query[$key] = $value;
            }
        }

        return new DynamicResponseModel($this->processRestRequest('GET', '/messages/outbound', $query));
    }

    /**
     * Get information related to a specific sent message.
     *
     * @param string $id The ID of the Message for which we want details.
     */
    public function getOutboundMessageDetails(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/messages/outbound/%s/details', $id)),
        );
    }

    /**
     * Get the raw content for a message that was sent.
     * This response
     *
     * @param  string $id The ID of the message for which we want a dump.
     */
    public function getOutboundMessageDump(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/messages/outbound/%s/dump', $id)),
        );
    }
}
