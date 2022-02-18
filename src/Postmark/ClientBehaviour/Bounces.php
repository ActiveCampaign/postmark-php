<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;

use function sprintf;

/** @internal \Postmark */
trait Bounces
{
    /**
     * Get a batch of bounces to be processed.
     *
     * @link http://developer.postmarkapp.com/developer-api-bounce.html#bounce-types)
     *
     * @param int         $count         Number of bounces to retrieve
     * @param int         $offset        How many bounces to skip (when paging through bounces.)
     * @param string|null $type          The bounce type.
     * @param bool|null   $inactive      Specifies if the bounce caused Postmark to deactivate this email.
     * @param string|null $emailFilter   Filter by email address
     * @param string|null $tag           Filter by tag
     * @param string|null $messageID     Filter by MessageID
     * @param string|null $fromdate      Filter for bounces after is date.
     * @param string|null $todate        Filter for bounces before this date.
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getBounces(
        int $count = 100,
        int $offset = 0,
        ?string $type = null,
        ?bool $inactive = null,
        ?string $emailFilter = null,
        ?string $tag = null,
        ?string $messageID = null,
        ?string $fromdate = null,
        ?string $todate = null,
        ?string $messagestream = null
    ): DynamicResponseModel {
        $query = [];
        $query['type'] = $type;
        $query['inactive'] = $this->stringifyBoolean($inactive);
        $query['emailFilter'] = $emailFilter;
        $query['tag'] = $tag;
        $query['messageID'] = $messageID;
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['fromdate'] = $fromdate;
        $query['todate'] = $todate;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel($this->processRestRequest('GET', '/bounces', $query));
    }

    /**
     * Locate information on a specific email bounce.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     *
     * @param int|numeric-string $id The ID of the bounce to get.
     */
    public function getBounce($id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/bounces/%s', $id))
        );
    }

    /**
     * Get a "dump" for a specific bounce.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     *
     * @param int|numeric-string $id The ID of the bounce for which we want a dump.
     */
    public function getBounceDump($id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('GET', sprintf('/bounces/%s/dump', $id))
        );
    }

    /**
     * Cause the email address associated with a Bounce to be reactivated.
     *
     * If the $id value is greater than PHP_INT_MAX, the ID can be passed as a string.
     *
     * @param int|numeric-string $id The bounce which has a deactivated email address.
     */
    public function activateBounce($id): DynamicResponseModel
    {
        return new DynamicResponseModel(
            $this->processRestRequest('PUT', sprintf('/bounces/%s/activate', $id))
        );
    }
}
