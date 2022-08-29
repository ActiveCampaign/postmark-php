<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;

use function sprintf;

/** @internal \Postmark */
trait InboundMessages
{
    /**
     * Get messages sent to the inbound email address associated with this Server.
     *
     * @param int         $count       The number of inbound messages to retrieve in the request (defaults to 100)
     * @param int         $offset      The number of messages to 'skip' when 'paging' through messages (defaults to 0)
     * @param string|null $recipient   Filter by the message recipient
     * @param string|null $fromEmail   Filter by the message sender
     * @param string|null $tag         Filter by the message tag
     * @param string|null $subject     Filter by the message subject
     * @param string|null $mailboxHash Filter by the mailboxHash
     * @param string|null $status      Filter by status ('blocked' or 'processed')
     * @param string|null $fromdate    Filter to messages on or after YYYY-MM-DD
     * @param string|null $todate      Filter to messages on or before YYYY-MM-DD
     */
    public function getInboundMessages(
        int $count = 100,
        int $offset = 0,
        ?string $recipient = null,
        ?string $fromEmail = null,
        ?string $tag = null,
        ?string $subject = null,
        ?string $mailboxHash = null,
        ?string $status = null,
        ?string $fromdate = null,
        ?string $todate = null
    ): DynamicResponseModel {
        $query = [];
        $query['recipient'] = $recipient;
        $query['fromemail'] = $fromEmail;
        $query['tag'] = $tag;
        $query['subject'] = $subject;
        $query['mailboxhash'] = $mailboxHash;
        $query['count'] = $count;
        $query['status'] = $status;
        $query['offset'] = $offset;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;

        return new DynamicResponseModel($this->processRestRequest('GET', '/messages/inbound', $query));
    }

    /**
     * Get details for a specific inbound message.
     *
     * @param string $id The ID of the message for which we went to get details.
     */
    public function getInboundMessageDetails(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/messages/inbound/%s/details', $id)),
        );
    }

    /**
     * Allow an inbound message to be processed, even though the filtering rules would normally
     * prevent it from being processed.
     *
     * @param string $id The ID for a message that we wish to unblock.
     */
    public function bypassInboundMessageRules(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/messages/inbound/%s/bypass', $id)),
        );
    }

    /**
     * Request that Postmark retry POSTing the specified message to the Server's Inbound Hook.
     *
     * @param string $id The ID for a message that we wish retry the inbound hook for.
     */
    public function retryInboundMessageHook(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/messages/inbound/%s/retry', $id)),
        );
    }

    /**
     * Create an Inbound Rule to block messages from a single email address, or an entire domain.
     *
     * @param string $rule The email address (or domain) that will be blocked.
     */
    public function createInboundRuleTrigger(string $rule): DynamicResponseModel
    {
        $body = [];
        $body['Rule'] = $rule;

        return new DynamicResponseModel(
            $this->processRestRequest('POST', '/triggers/inboundrules', $body),
        );
    }

    /**
     * Get a list of all existing Inbound Rule Triggers.
     *
     * @param int $count  The number of rule triggers to return with this request.
     * @param int $offset The number of triggers to 'skip' when 'paging' through rule triggers.
     */
    public function listInboundRuleTriggers(int $count = 100, int $offset = 0): DynamicResponseModel
    {
        $query = [];

        $query['count'] = $count;
        $query['offset'] = $offset;

        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/triggers/inboundrules', $query),
        );
    }

    /**
     * Delete an Inbound Rule Trigger.
     *
     * @param int $id The ID of the rule trigger we wish to delete.
     */
    public function deleteInboundRuleTrigger(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/triggers/inboundrules/%s', $id)),
        );
    }
}
