<?php

declare(strict_types=1);

namespace Postmark\Models;

use Exception;

/**
 * The exception thrown when the Postmark Client receives an error from the API.
 */
class PostmarkException extends Exception
{
    var $message;
    var $httpStatusCode;
    var $postmarkApiErrorCode;
}
