-----------------------------
Postmark\\PostmarkAdminClient
-----------------------------

.. php:namespace: Postmark

.. php:class:: PostmarkAdminClient

    The PostmarkAdminClient allows users to access and modify
    "Account-wide" settings. At this time the API supports
    management of the "Sender Signatures", and "Servers."

    .. php:attr:: BASE_URL

        string

        BASE_URL is "https://api.postmarkapp.com" - you may modify this value
        to disable SSL support, but it is not recommended.

    .. php:attr:: authorization_token

        protected

    .. php:attr:: authorization_header

        protected

    .. php:method:: __construct($account_token)

        Create a new PostmarkAdminClient.

        :type $account_token: string
        :param $account_token: is NOT the same as a Server Token - you can get your account token from this page: https://postmarkapp.com/account/edit

    .. php:method:: getServer($id)

        Request a given server by ID.

        :type $id: int
        :param $id: The Id for the server you wish to retrieve.
        :returns: DynamicResponseModel

    .. php:method:: listServers($count = 100, $offset = 0, $name = NULL)

        Get a list of all servers configured on the account.

        :param $count:
        :param $offset:
        :param $name:
        :returns: DynamicResponseModel

    .. php:method:: deleteServer($id)

        Delete a Server used for sending/receiving mail. NOTE: To protect your
        account, you'll need to
        contact support and request that they enable this feature on your account
        before you can use this
        client to delete Servers.

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: editServer($id, $name = NULL, $color = NULL, $rawEmailEnabled = NULL, $smtpApiActivated = NULL, $inboundHookUrl = NULL, $bounceHookUrl = NULL, $openHookUrl = NULL, $postFirstOpenOnly = NULL, $trackOpens = NULL, $inboundDomain = NULL, $inboundSpamThreshold = NULL)

        Modify an existing Server. Any parameters passed with NULL will be
        ignored (their existing values will not be modified).

        :param $id:
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

    .. php:method:: createServer($name, $color = NULL, $rawEmailEnabled = NULL, $smtpApiActivated = NULL, $inboundHookUrl = NULL, $bounceHookUrl = NULL, $openHookUrl = NULL, $postFirstOpenOnly = NULL, $trackOpens = NULL, $inboundDomain = NULL, $inboundSpamThreshold = NULL)

        Create a new Server. Any parameters passed with NULL will be
        ignored (their default values will be used).

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

    .. php:method:: listSenderSignatures($count = 100, $offset = 0)

        Get a "page" of Sender Signatures.

        :param $count:
        :param $offset:
        :returns: DynamicResponseModel

    .. php:method:: getSenderSignature($id)

        Get information for a sepcific Sender Signature.

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: createSenderSignature($fromEmail, $name, $replyToEmail = NULL)

        Create a new Sender Signature for a given email address. Note that you
        will need to
        "verify" this Sender Signature by following a link that will be emailed to
        the "fromEmail"
        address specified when calling this method.

        :param $fromEmail:
        :param $name:
        :param $replyToEmail:
        :returns: DynamicResponseModel

    .. php:method:: editSenderSignature($id, $name = NULL, $replyToEmail = NULL)

        Alter the defaults for a Sender Signature.

        :param $id:
        :param $name:
        :param $replyToEmail:
        :returns: DynamicResponseModel

    .. php:method:: deleteSenderSignature($id)

        Delete a Sender Signature with the given ID.

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: resendSenderSignatureConfirmation($id)

        Cause a new verification email to be sent for an existing (unverified)
        Sender Signature.
        Sender Signatures require verification before they may be used to send
        email through the Postmark API.

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: verifySenderSignatureSPF($id)

        Request that the Postmark API updates verify the SPF records associated
        with the Sender Signature's email address's domain. Configuring SPF is not
        required to use
        Postmark, but it is highly recommended, and can improve delivery rates.

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: requestNewSenderSignatureDKIM($id)

        Cause a new DKIM key to be generated and associated with the Sender
        Signature. This key must be added
        to your email domain's DNS records. Including DKIM is not required, but is
        recommended. For more information
        on DKIM and its purpose, see http://www.dkim.org/

        :param $id:
        :returns: DynamicResponseModel

    .. php:method:: processRestRequest($method = NULL, $path = NULL, $body = NULL)

        :param $method:
        :param $path:
        :param $body:
