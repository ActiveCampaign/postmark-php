<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace Postmark\Models;

/**
 * The exception thrown when the Postmark Client recieves an error from the API.
 */
class PostmarkException extends \Exception {
	public $message;
	public $HttpStatusCode;
	public $PostmarkApiErrorCode;

    /**
     * @return mixed
     */
    public function getPostmarkApiErrorCode()
    {
        return $this->PostmarkApiErrorCode;
    }

    /**
     * @param mixed $postmarkApiErrorCode
     * @return PostmarkException
     */
    public function setPostmarkApiErrorCode($postmarkApiErrorCode)
    {
        $this->PostmarkApiErrorCode = $postmarkApiErrorCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHttpStatusCode()
    {
        return $this->HttpStatusCode;
    }

    /**
     * @param mixed $httpStatusCode
     * @return PostmarkException
     */
    public function setHttpStatusCode($httpStatusCode)
    {
        $this->HttpStatusCode = $httpStatusCode;
        return $this;
    }

}