<?php


namespace Zan\Framework\Network\Common\Exception;


use Exception;
use Zan\Framework\Foundation\Exception\SystemException;

class UnexpectedResponseException extends SystemException
{
    public $metaData;

    public function __construct($message = "", $code = 0, Exception $previous = NULL, array $metaData = NULL)
    {
        parent::__construct($message, $code, $previous);
        $this->metaData = $metaData;
    }
}