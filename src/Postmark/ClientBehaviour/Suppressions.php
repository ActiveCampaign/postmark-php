<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Suppressions\SuppressionChangeRequest;

trait Suppressions
{
    /**
     * Create Suppressions for the specified recipients.
     *
     * @param list<SuppressionChangeRequest> $suppressionChanges Array of SuppressionChangeRequest objects that specify what recipients to suppress.
     * @param string|null $messageStream      Message stream where the recipients should be suppressed. If not provided, they will be suppressed on the default transactional stream.
     *
     *      Suppressions will be generated with a Customer Origin and will have a ManualSuppression reason.
     */
    public function createSuppressions(
        array $suppressionChanges = [],
        string $messageStream = 'outbound'
    ): DynamicResponseModel {
        $body = [];
        $body['Suppressions'] = $suppressionChanges;

        return new DynamicResponseModel(
            $this->processRestRequest('POST', sprintf('/message-streams/%s/suppressions', $messageStream), $body)
        );
    }

    /**
     * Reactivate Suppressions for the specified recipients.
     *
     * @param string $suppressionChanges Array of SuppressionChangeRequest objects that specify what recipients to reactivate.
     * @param string $messageStream      Message stream where the recipients should be reactivated. If not provided, they will be reactivated on the default transactional stream.
     *
     *      Only 'Customer' origin 'ManualSuppression' suppressions and 'Recipient' origin 'HardBounce' suppressions can be reactivated.
     */
    public function deleteSuppressions($suppressionChanges = [], $messageStream = null): DynamicResponseModel
    {
        $body = [];
        $body['Suppressions'] = $suppressionChanges;

        if ($messageStream === null) {
            $messageStream = 'outbound';
        }

        return new DynamicResponseModel($this->processRestRequest('POST', "/message-streams/$messageStream/suppressions/delete", $body));
    }

    /**
     * List Suppressions that match the provided query parameters.
     *
     * @param string $messageStream     Filter Suppressions by MessageStream. If not provided, Suppressions for the default transactional stream will be returned. (optional)
     * @param string $suppressionReason Filter Suppressions by reason. E.g.: HardBounce, SpamComplaint, ManualSuppression. (optional)
     * @param string $origin            Filter Suppressions by the origin that created them. E.g.: Customer, Recipient, Admin. (optional)
     * @param string $fromDate          Filter suppressions from the date specified - inclusive. (optional)
     * @param string $toDate            Filter suppressions up to the date specified - inclusive. (optional)
     * @param string $emailAddress      Filter by a specific email address. (optional)
     */
    public function getSuppressions($messageStream = null, $suppressionReason = null, $origin = null, $fromDate = null, $toDate = null, $emailAddress = null): DynamicResponseModel
    {
        $query = [];
        $query['SuppressionReason'] = $suppressionReason;
        $query['Origin'] = $origin;
        $query['FromDate'] = $fromDate;
        $query['ToDate'] = $toDate;
        $query['EmailAddress'] = $emailAddress;

        if ($messageStream === null) {
            $messageStream = 'outbound';
        }

        return new DynamicResponseModel($this->processRestRequest('GET', "/message-streams/$messageStream/suppressions/dump", $query));
    }
}
