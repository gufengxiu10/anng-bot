<?php

declare(strict_types=1);

namespace app;

use Anng\lib\Exception as LibException;
use Throwable;

class Exception extends LibException
{
    public function render(Throwable $th)
    {
        dump(1);
        return parent::render($th);
    }
}
