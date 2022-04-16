<?php

namespace src\Persistence\Exceptions;

use Exception;
use Throwable;

final class ConfigurationLoadingException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}