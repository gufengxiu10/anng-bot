<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Config as LibConfig;
use Anng\lib\Facade;

class Config extends Facade
{
    protected static function getFacadeClass()
    {
        return LibConfig::class;
    }
}
