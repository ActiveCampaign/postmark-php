<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;

use function sprintf;

/** @internal \Postmark */
trait MessageStreams
{
    /**
     * Create a new message stream on your server
     *
     * Currently, you cannot create multiple inbound streams.
     *
     * @param non-empty-string $id                Identifier for your message stream, unique at server level.
     * @param string           $messageStreamType Type of the message stream. Possible values:
     *                                            ["Transactional", "Inbound", "Broadcasts"].
     * @param string           $name              Friendly name for your message stream.
     * @param string|null      $description       Friendly description for your message stream. (optional)
     */
    public function createMessageStream(
        string $id,
        string $messageStreamType,
        string $name,
        ?string $description = null
    ): DynamicResponseModel {
        $body = [];
        $body['ID'] = $id;
        $body['MessageStreamType'] = $messageStreamType;
        $body['Name'] = $name;
        $body['Description'] = $description;

        return new DynamicResponseModel($this->processRestRequest('POST', '/message-streams', $body));
    }

    /**
     * Edit the properties of a message stream.
     *
     * @param non-empty-string $id          The identifier for the stream you are trying to update.
     * @param string|null      $name        New friendly name to use. (optional)
     * @param string|null      $description New description to use. (optional)
     */
    public function editMessageStream(
        string $id,
        ?string $name = null,
        ?string $description = null
    ): DynamicResponseModel {
        $body = [];
        $body['Name'] = $name;
        $body['Description'] = $description;

        return new DynamicResponseModel($this->processRestRequest('PATCH', '/message-streams/' . $id, $body));
    }

    /**
     * Retrieve details about a message stream.
     *
     * @param non-empty-string $id Identifier of the stream to retrieve details for.
     */
    public function getMessageStream(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', '/message-streams/' . $id));
    }

    /**
     * Retrieve all message streams on the server.
     *
     * @param non-empty-string $messageStreamType      Filter by stream type. Possible values:
     *                                                 ["Transactional", "Inbound", "Broadcasts", "All"]. Default: All
     * @param non-empty-string $includeArchivedStreams Include archived streams in the result. Defaults to: false.
     */
    public function listMessageStreams(
        string $messageStreamType = 'All',
        string $includeArchivedStreams = 'false'
    ): DynamicResponseModel {
        $query = [];
        $query['MessageStreamType'] = $messageStreamType;
        $query['IncludeArchivedStreams'] = $includeArchivedStreams;

        return new DynamicResponseModel($this->processRestRequest('GET', '/message-streams', $query));
    }

    /**
     * Archive a message stream. This will disable sending/receiving messages via that stream.
     * The stream will also stop being shown in the Postmark UI.
     * Once a stream has been archived, it will be deleted (alongside associated data) at the ExpectedPurgeDate
     * in the response.
     *
     * @param non-empty-string $id The identifier for the stream you are trying to update.
     */
    public function archiveMessageStream(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/message-streams/%s/archive', $id))
        );
    }

    /**
     * Un-archive a message stream. This will resume sending/receiving via that stream.
     * The stream will also re-appear in the Postmark UI.
     * A stream can be unarchived only before the stream ExpectedPurgeDate.
     *
     * @param non-empty-string $id Identifier of the stream to un-archive.
     */
    public function unarchiveMessageStream(string $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/message-streams/%s/unarchive', $id))
        );
    }
}
