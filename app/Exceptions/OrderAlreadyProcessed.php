<?php


namespace App\Exceptions;

class OrderAlreadyProcessed extends GeneralException
{
    public function __construct($message = '', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
