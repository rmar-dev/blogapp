<?php


namespace App\Exceptions;


use Throwable;

class InvalidConfirmationTokenException extends \Exception
{
    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null)
    {
        parent::__construct("Confirmation Token is invalid.", $code, $previous);
    }
}