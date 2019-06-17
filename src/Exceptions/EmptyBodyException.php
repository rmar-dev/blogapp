<?php


namespace App\Exceptions;

use Exception as ExceptionAlias;
use Throwable;

class EmptyBodyException extends ExceptionAlias
{
    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null)
    {
        parent::__construct('The body of the POST/PUT method cannot be empty', $code, $previous);
    }
}