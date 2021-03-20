<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;
use Anng\lib\Route as BaseRoute;

class Route extends Facade
{
    protected static function getFacadeClass()
    {
        return BaseRoute::class;
    }
}
