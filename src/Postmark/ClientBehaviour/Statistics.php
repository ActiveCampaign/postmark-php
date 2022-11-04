<?php

declare(strict_types=1);

namespace Postmark\ClientBehaviour;

use Postmark\Models\DynamicResponseModel;

use function sprintf;

/** @internal \Postmark */
trait Statistics
{
    /**
     * Get an overview of the delivery statistics for all email that has been sent through this Server.
     */
    public function getDeliveryStatistics(): DynamicResponseModel
    {
        return new DynamicResponseModel($this->processRestRequest('GET', '/deliverystats'));
    }

    /**
     * Get statistics for tracked messages, optionally filtering by various open event properties.
     *
     * @param int         $count         The number of open statistics to retrieve in this request.
     * @param int         $offset        The number of statistics to 'skip' when paging through statistics.
     * @param string|null $recipient     Filter by recipient.
     * @param string|null $tag           Filter by tag.
     * @param string|null $clientName    Filter by Email Client name.
     * @param string|null $clientCompany Filter by Email Client Company's name.
     * @param string|null $clientFamily  Filter by Email Client's Family name.
     * @param string|null $osName        Filter by Email Client's Operating System Name.
     * @param string|null $osFamily      Filter by Email Client's Operating System's Family.
     * @param string|null $osCompany     Filter by Email Client's Operating System's Company.
     * @param string|null $platform      Filter by Email Client's Platform Name.
     * @param string|null $country       Filter by Country.
     * @param string|null $region        Filter by Region.
     * @param string|null $city          Filter by City.
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOpenStatistics(
        int $count = 100,
        int $offset = 0,
        string|null $recipient = null,
        string|null $tag = null,
        string|null $clientName = null,
        string|null $clientCompany = null,
        string|null $clientFamily = null,
        string|null $osName = null,
        string|null $osFamily = null,
        string|null $osCompany = null,
        string|null $platform = null,
        string|null $country = null,
        string|null $region = null,
        string|null $city = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        $query = [];
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['recipient'] = $recipient;
        $query['tag'] = $tag;
        $query['client_name'] = $clientName;
        $query['client_company'] = $clientCompany;
        $query['client_family'] = $clientFamily;
        $query['os_name'] = $osName;
        $query['os_family'] = $osFamily;
        $query['os_company'] = $osCompany;
        $query['platform'] = $platform;
        $query['country'] = $country;
        $query['region'] = $region;
        $query['city'] = $city;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/messages/outbound/opens', $query),
        );
    }

    /**
     * Get statistics for tracked messages, optionally filtering by various click event properties.
     *
     * @param int         $count         The number of click statistics to retrieve in this request.
     * @param int         $offset        The number of statistics to 'skip' when paging through statistics.
     * @param string|null $recipient     Filter by recipient.
     * @param string|null $tag           Filter by tag.
     * @param string|null $clientName    Filter by Email Client name.
     * @param string|null $clientCompany Filter by Email Client Company's name.
     * @param string|null $clientFamily  Filter by Email Client's Family name.
     * @param string|null $osName        Filter by Email Client's Operating System Name.
     * @param string|null $osFamily      Filter by Email Client's Operating System's Family.
     * @param string|null $osCompany     Filter by Email Client's Operating System's Company.
     * @param string|null $platform      Filter by Email Client's Platform Name.
     * @param string|null $country       Filter by Country.
     * @param string|null $region        Filter by Region.
     * @param string|null $city          Filter by City.
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getClickStatistics(
        int $count = 100,
        int $offset = 0,
        string|null $recipient = null,
        string|null $tag = null,
        string|null $clientName = null,
        string|null $clientCompany = null,
        string|null $clientFamily = null,
        string|null $osName = null,
        string|null $osFamily = null,
        string|null $osCompany = null,
        string|null $platform = null,
        string|null $country = null,
        string|null $region = null,
        string|null $city = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        $query = [];
        $query['count'] = $count;
        $query['offset'] = $offset;
        $query['recipient'] = $recipient;
        $query['tag'] = $tag;
        $query['client_name'] = $clientName;
        $query['client_company'] = $clientCompany;
        $query['client_family'] = $clientFamily;
        $query['os_name'] = $osName;
        $query['os_family'] = $osFamily;
        $query['os_company'] = $osCompany;
        $query['platform'] = $platform;
        $query['country'] = $country;
        $query['region'] = $region;
        $query['city'] = $city;
        $query['messagestream'] = $messagestream;

        return new DynamicResponseModel(
            $this->processRestRequest('GET', '/messages/outbound/clicks', $query),
        );
    }

    /**
     * Get information about individual opens for a sent message.
     *
     * @param string $id     The ID for the message that we want statistics for.
     * @param int    $count  How many statistics should we retrieve?
     * @param int    $offset How many should we 'skip' when 'paging' through statistics.
     */
    public function getOpenStatisticsForMessage(
        string $id,
        int $count = 100,
        int $offset = 0,
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest(
                'GET',
                sprintf('/messages/outbound/opens/%s', $id),
                [
                    'count' => $count,
                    'offset' => $offset,
                ],
            ),
        );
    }

    /**
     * Get information about individual clicks for a sent message.
     *
     * @param string $id     The ID for the message that we want statistics for.
     * @param int    $count  How many statistics should we retrieve?
     * @param int    $offset How many should we 'skip' when 'paging' through statistics.
     */
    public function getClickStatisticsForMessage(
        string $id,
        int $count = 100,
        int $offset = 0,
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest(
                'GET',
                sprintf('/messages/outbound/clicks/%s', $id),
                [
                    'count' => $count,
                    'offset' => $offset,
                ],
            ),
        );
    }

    private function stats(
        string $path,
        string|null $tag,
        string|null $fromDate,
        string|null $toDate,
        string|null $messageStream,
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest(
                'GET',
                $path,
                [
                    'tag' => $tag,
                    'fromdate' => $fromDate,
                    'todate' => $toDate,
                    'messagestream' => $messageStream,
                ],
            ),
        );
    }

    /**
     * Get an overview of the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundOverviewStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get send statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundSendStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/sends', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get bounce statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundBounceStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/bounces', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get SPAM complaint statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundSpamComplaintStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/spam', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get bounce statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundTrackedStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/tracked', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get open statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundOpenStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/opens', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get platform statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundPlatformStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/opens/platforms', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get email client statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundEmailClientStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/opens/emailclients', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get reading times for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag      Filter by tag.
     * @param string|null $fromdate must be of the format 'YYYY-MM-DD'
     * @param string|null $todate   must be of the format 'YYYY-MM-DD'
     */
    public function getOutboundReadTimeStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
    ): DynamicResponseModel {
        return new DynamicResponseModel(
            $this->processRestRequest(
                'GET',
                '/stats/outbound/opens/readtimes',
                [
                    'tag' => $tag,
                    'fromdate' => $fromdate,
                    'todate' => $todate,
                ],
            ),
        );
    }

    /**
     * Get click statistics for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundClickStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/clicks', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get information about which browsers were used to open tracked links for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundClickBrowserFamilyStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/clicks/browserfamilies', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get information about which browsers platforms (Desktop, Mobile, etc.) were used to open
     * tracked links for the messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundClickBrowserPlatformStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/clicks/platforms', $tag, $fromdate, $todate, $messagestream);
    }

    /**
     * Get information about part of the message (HTML or Text)
     * tracked links were clicked from in messages sent using this Server,
     * optionally filtering on message tag, and a to and from date.
     *
     * @param string|null $tag           Filter by tag.
     * @param string|null $fromdate      must be of the format 'YYYY-MM-DD'
     * @param string|null $todate        must be of the format 'YYYY-MM-DD'
     * @param string|null $messagestream Filter by Message Stream ID. If null, the default "outbound"
     *                                   transactional stream will be used.
     */
    public function getOutboundClickLocationStatistics(
        string|null $tag = null,
        string|null $fromdate = null,
        string|null $todate = null,
        string|null $messagestream = null,
    ): DynamicResponseModel {
        return $this->stats('/stats/outbound/clicks/location', $tag, $fromdate, $todate, $messagestream);
    }
}
