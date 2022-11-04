<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;
use Postmark\Models\Suppressions\SuppressionChangeRequest;

use function sprintf;

/** @internal \Postmark */
trait Suppressions
{
    /**
     * Create Suppressions for the specified recipients.
     *
     * Suppressions will be generated with a "Customer" Origin and will have a "ManualSuppression" reason.
     *
     * @param list<SuppressionChangeRequest> $suppressionChanges Array of SuppressionChangeRequest objects that
     *                                                           specify what recipients to suppress.
     * @param string                         $messageStream      Message stream where the recipients should be
     *                                                           suppressed. If not provided, they will be suppressed
     *                                                           on the default transactional stream.
     */
    public function createSuppressions(
        array $suppressionChanges = [],
        string $messageStream = 'outbound',
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest(
                'POST',
                sprintf('/message-streams/%s/suppressions', $messageStream),
                ['Suppressions' => $suppressionChanges],
            ),
        );
    }

    /**
     * Reactivate Suppressions for the specified recipients.
     *
     * Only 'Customer' origin 'ManualSuppression' suppressions and 'Recipient' origin 'HardBounce'
     * suppressions can be reactivated.
     *
     * @param list<SuppressionChangeRequest> $suppressionChanges Array of SuppressionChangeRequest objects that specify
     *                                                           what recipients to reactivate.
     * @param string                         $messageStream      Message stream where the recipients should be
     *                                                           reactivated. If not provided, they will be reactivated
     *                                                           on the default transactional stream.
     */
    public function deleteSuppressions(
        array $suppressionChanges = [],
        string $messageStream = 'outbound',
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest(
                'POST',
                sprintf('/message-streams/%s/suppressions/delete', $messageStream),
                ['Suppressions' => $suppressionChanges],
            ),
        );
    }

    /**
     * List Suppressions that match the provided query parameters.
     *
     * @param string      $messageStream     Filter Suppressions by MessageStream. If not provided, Suppressions for
     *                                       the default transactional stream will be returned. (optional)
     * @param string|null $suppressionReason Filter Suppressions by reason. E.g.: HardBounce, SpamComplaint,
     *                                       ManualSuppression. (optional)
     * @param string|null $origin            Filter Suppressions by the origin that created them. E.g.: Customer,
     *                                       Recipient, Admin. (optional)
     * @param string|null $fromDate          Filter suppressions from the date specified - inclusive. (optional)
     * @param string|null $toDate            Filter suppressions up to the date specified - inclusive. (optional)
     * @param string|null $emailAddress      Filter by a specific email address. (optional)
     */
    public function getSuppressions(
        string $messageStream = 'outbound',
        string|null $suppressionReason = null,
        string|null $origin = null,
        string|null $fromDate = null,
        string|null $toDate = null,
        string|null $emailAddress = null,
    ): DynamicResponseModel {
        $query = [];
        $query['SuppressionReason'] = $suppressionReason;
        $query['Origin'] = $origin;
        $query['FromDate'] = $fromDate;
        $query['ToDate'] = $toDate;
        $query['EmailAddress'] = $emailAddress;

        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/message-streams/%s/suppressions/dump', $messageStream), $query),
        );
    }
}
