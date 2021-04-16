<?php

declare(strict_types=1);


namespace Anng\lib\exception;

use Exception;
use Throwable;

class DbException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
