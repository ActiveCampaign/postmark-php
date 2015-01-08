------------------------
Postmark\\PostmarkClient
------------------------

.. php:namespace: Postmark

.. php:class:: PostmarkClient

    PostmarkClient provides the main functionality used to send an analyze email on a "per-Server"
    basis. If you'd like to manage "Account-wide" configuration, see the PostmarkAdminClient.

    .. php:attr:: BASE_URL

        string

        BASE_URL is "https://api.postmarkapp.com" - you may modify this value
        to disable SSL support, but it is not recommended.

    .. php:attr:: authorization_token

        protected

    .. php:attr:: authorization_header

        protected

    .. php:method:: __construct($server_token)

        Create a new PostmarkClient.

        :type $server_token: string
        :param $server_token: The token associated with "Server" you'd like to use to send/receive email from.

    .. php:method:: sendEmail($from, $to, $subject, $htmlBody = NULL, $textBody = NULL, $tag = NULL, $trackOpens = true, $replyTo = NULL, $cc = NULL, $bcc = NULL, $headers = NULL, $attachments = NULL)

        Send an email.

        :param $from:
        :param $to:
        :param $subject:
        :param $htmlBody:
        :param $textBody:
        :param $tag:
        :param $trackOpens:
        :param $replyTo:
        :param $cc:
        :param $bcc:
        :param $headers:
        :param $attachments:
        :returns: DynamicResponseModel

    .. php:method:: sendEmailBatch($emailBatch = [])

        Send multiple emails as a batch

        :param $emailBatch:
        :returns: DynamicResponseModel

    .. php:method:: getDeliveryStatistics()

        Get an overview of the delivery statistics for all email that has been
        sent through this Server.

        :returns: DynamicResponseModel

    .. php:method:: getBounces($count = 100, $offset = 0, $type = NULL, $inactive = NULL, $emailFilter = NULL, $tag = NULL, $messageID = NULL)

        Get a batch of bounces to be processed.

        :param $count:
        :param $offset:
        :param $type:
        :param $inactive:
        :param $emailFilter:
        :param $tag:
        :param $messageID:
        :returns: DynamicResponseModel

    .. php:method:: getBounce($id)

        Locate information on a specific email bounce.

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: getBounceDump($id)

        Get a "dump" for a specific bounce.

        :param $id:
        :returns: string

    .. php:method:: activateBounce($id)

        Cause the email address associated with a Bounce to be reactivated.

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: getBounceTags()

        Get the list of tags associated with messages that have bounced.

        :returns: Array

    .. php:method:: getServer()

        Get the settings for the server associated with this PostmarkClient
        instance
        (defined by the $server_token you passed when instantiating this client)

        :returns: DynamicResponseModel

    .. php:method:: editServer($name = NULL, $color = NULL, $rawEmailEnabled = NULL, $smtpApiActivated = NULL, $inboundHookUrl = NULL, $bounceHookUrl = NULL, $openHookUrl = NULL, $postFirstOpenOnly = NULL, $trackOpens = NULL, $inboundDomain = NULL, $inboundSpamThreshold = NULL)

        Modify the associated Server. Any parameters passed with NULL will be
        ignored (their existing values will not be modified).

        :param $name:
        :param $color:
        :param $rawEmailEnabled:
        :param $smtpApiActivated:
        :param $inboundHookUrl:
        :param $bounceHookUrl:
        :param $openHookUrl:
        :param $postFirstOpenOnly:
        :param $trackOpens:
        :param $inboundDomain:
        :param $inboundSpamThreshold:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundMessages($count = 100, $offset = 0, $recipient = NULL, $fromEmail = NULL, $tag = NULL, $subject = NULL)

        Search messages that have been sent using this Server.

        :param $count:
        :param $offset:
        :param $recipient:
        :param $fromEmail:
        :param $tag:
        :param $subject:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundMessageDetails($id)

        Get information related to a specific sent message.

        :type $id: integer
        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundMessageDump($id)

        Get the raw content for a message that was sent.

        :param $id:
        :returns: string

    .. php:method:: getInboundMessages($count = 100, $offset = 0, $recipient = NULL, $fromEmail = NULL, $tag = NULL, $subject = NULL, $mailboxHash = NULL, $status = NULL)

        Get messages sent to the inbound email address associated with this
        Server.

        :param $count:
        :param $offset:
        :param $recipient:
        :param $fromEmail:
        :param $tag:
        :param $subject:
        :param $mailboxHash:
        :param $status:
        :returns: DynamicResponseModel

    .. php:method:: getInboundMessageDetails($id)

        Get details for a specific inbound message.

        :type $id: integer
        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: bypassInboundMessageRules($id)

        Allow an inbound message to be processed, even though the filtering rules
        would normally
        prevent it from being processed.

        :type $id: integer
        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: getOpenStatistics($count = 100, $offset = 0, $recipient = NULL, $tag = NULL, $clientName = NULL, $clientCompany = NULL, $clientFamily = NULL, $osName = NULL, $osFamily = NULL, $osCompany = NULL, $platform = NULL, $country = NULL, $region = NULL, $city = NULL)

        Get statistics for tracked messages, optionally filtering by various open
        event properties.

        :param $count:
        :param $offset:
        :param $recipient:
        :param $tag:
        :param $clientName:
        :param $clientCompany:
        :param $clientFamily:
        :param $osName:
        :param $osFamily:
        :param $osCompany:
        :param $platform:
        :param $country:
        :param $region:
        :param $city:
        :returns: DynamicResponseModel

    .. php:method:: getOpenStatisticsForMessage($id, $count = 100, $offset = 0)

        Get information about individual opens for a sent message.

        :param $id:
        :param $count:
        :param $offset:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundOverviewStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get an overview of the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundSendStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get send statistics for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundBounceStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get bounce statistics for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundSpamComplaintStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get SPAM complaint statistics for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundTrackedStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get bounce statistics for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundOpenStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get open statistics for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundPlatformStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get platform statistics for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundEmailClientStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get email client statistics for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: getOutboundReadTimeStatistics($tag = NULL, $fromdate = NULL, $todate = NULL)

        Get reading times for the messages sent using this Server,
        optionally filtering on message tag, and a to and from date.

        :param $tag:
        :param $fromdate:
        :param $todate:
        :returns: DynamicResponseModel

    .. php:method:: createTagTrigger($matchName, $trackOpens = true)

        Create a Tag Trigger.

        :param $matchName:
        :param $trackOpens:
        :returns: DynamicResponseModel

    .. php:method:: deleteTagTrigger($id)

        Delete a Tag Trigger with the given ID.

        :type $id: integer
        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: searchTagTriggers($count = 100, $offset = 0, $matchName = NULL)

        Locate Tag Triggers matching the filter criteria.

        :param $count:
        :param $offset:
        :param $matchName:
        :returns: DynamicResponseModel

    .. php:method:: editTagTrigger($id, $matchName, $trackOpens = true)

        Edit an existing Tag Trigger

        :param $id:
        :param $matchName:
        :param $trackOpens:
        :returns: DynamicResponseModel

    .. php:method:: getTagTrigger($id)

        Retrieve information related to the associated Tag Trigger

        :type $id: integer
        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: createInboundRuleTrigger($rule)

        Create an Inbound Rule to block messages from a single email address, or
        an entire domain.

        :param $rule:
        :returns: DynamicResponseModel

    .. php:method:: listInboundRuleTriggers($count = 100, $offset = 0)

        Get a list of all existing Inbound Rule Triggers.

        :type $count: integer
        :param $count:
        :type $offset: integer
        :param $offset:
        :returns: DynamicResponseModel

    .. php:method:: deleteInboundRuleTrigger($id)

        Delete an Inbound Rule Trigger.

        :type $id: integer
        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: processRestRequest($method = NULL, $path = NULL, $body = NULL)

        :param $method:
        :param $path:
        :param $body:
