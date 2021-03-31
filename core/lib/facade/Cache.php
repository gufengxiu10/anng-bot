<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\cache\Cache as BaseCache;
use Anng\lib\Facade;

class Cache extends Facade
{
    protected static function getFacadeClass()
    {
        return BaseCache::class;
    }
}
