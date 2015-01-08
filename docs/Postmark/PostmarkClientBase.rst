----------------------------
Postmark\\PostmarkClientBase
----------------------------

.. php:namespace: Postmark

.. php:class:: PostmarkClientBase

    This is the core class that interacts with the Postmark API. All clients should
    inherit fromt this class.

    .. php:attr:: BASE_URL

        string

        BASE_URL is "https://api.postmarkapp.com" - you may modify this value
        to disable SSL support, but it is not recommended.

    .. php:attr:: authorization_token

        protected

    .. php:attr:: authorization_header

        protected

    .. php:method:: __construct($token, $header)

        :param $token:
        :param $header:

    .. php:method:: processRestRequest($method = NULL, $path = NULL, $body = NULL)

        :param $method:
        :param $path:
        :param $body:
