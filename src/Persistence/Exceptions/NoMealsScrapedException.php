<?php

namespace src\Persistence\Exceptions;

use Exception;
use Throwable;

class NoMealsScrapedException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}