<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Header;
use Postmark\Models\Webhooks\HttpAuth;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\PostmarkClient;

use function sprintf;

/**
 * @internal \Postmark
 *
 * @see PostmarkClient
 *
 * @psalm-import-type HeaderList from PostmarkClient
 */
trait Webhooks
{
    /**
     * Get information about a specific webhook configuration.
     *
     * @param int $id The Id of the webhook configuration you wish to retrieve.
     */
    public function getWebhookConfiguration(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/webhooks/%s', $id))
        );
    }

    /**
     * Get all webhook configurations associated with the Server.
     *
     * @param string|null $messageStream Optional message stream to filter results by. If not provided,
     *                                   all configurations for the server will be returned.
     */
    public function getWebhookConfigurations(?string $messageStream = null): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/webhooks', ['MessageStream' => $messageStream])
        );
    }

    /**
     * Delete a webhook configuration.
     *
     * @param int $id The Id of the webhook configuration you wish to delete.
     */
    public function deleteWebhookConfiguration(int $id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('DELETE', sprintf('/webhooks/%s', $id))
        );
    }

    /**
     * Create a webhook configuration.
     *
     * @param string                            $url           The webhook URL.
     * @param string|null                       $messageStream Message stream this configuration should belong to.
     *                                                         If not provided, it will belong to the default
     *                                                         transactional stream.
     * @param HttpAuth|null                     $httpAuth      Optional Basic HTTP Authentication.
     * @param HeaderList|null                   $httpHeaders   Optional list of custom HTTP headers.
     * @param WebhookConfigurationTriggers|null $triggers      Optional triggers for this webhook configuration.
     */
    public function createWebhookConfiguration(
        string $url,
        ?string $messageStream = null,
        ?HttpAuth $httpAuth = null,
        ?array $httpHeaders = null,
        ?WebhookConfigurationTriggers $triggers = null
    ): DynamicResponseModel {
        $body = [];
        $body['Url'] = $url;
        $body['MessageStream'] = $messageStream;
        $body['HttpAuth'] = $httpAuth;
        $body['HttpHeaders'] = Header::listFromArray($httpHeaders);
        $body['Triggers'] = $triggers;

        return new DynamicResponseModel($this->processRestRequest('POST', '/webhooks', $body));
    }

    /**
     * Edit a webhook configuration.
     *
     * Any parameters passed with NULL will be ignored (their existing values will not be modified).
     *
     * @param int                               $id          The Id of the webhook configuration you wish to edit.
     * @param string|null                       $url         Optional webhook URL.
     * @param HttpAuth|null                     $httpAuth    Optional Basic HTTP Authentication.
     * @param HeaderList|null                   $httpHeaders Optional list of custom HTTP headers.
     * @param WebhookConfigurationTriggers|null $triggers    Optional triggers for this webhook configuration.
     */
    public function editWebhookConfiguration(
        int $id,
        ?string $url = null,
        ?HttpAuth $httpAuth = null,
        ?array $httpHeaders = null,
        ?WebhookConfigurationTriggers $triggers = null
    ): DynamicResponseModel {
        $body = [];
        $body['Url'] = $url;
        $body['HttpAuth'] = $httpAuth;
        $body['HttpHeaders'] = Header::listFromArray($httpHeaders);
        $body['Triggers'] = $triggers;

        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/webhooks/%s', $id), $body)
        );
    }
}
