<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Collection as LibCollection;
use Anng\lib\Facade;

class Collection extends Facade
{
    protected static function getFacadeClass()
    {
        return LibCollection::class;
    }
}
