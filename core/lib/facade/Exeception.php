<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\exception\Handle;
use Anng\lib\Facade;

class Exeception extends Facade
{
    protected static function getFacadeClass()
    {
        return Handle::class;
    }
}
