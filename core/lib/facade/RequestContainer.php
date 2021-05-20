<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;

class RequestContainer extends Facade
{
    protected static function getFacadeClass()
    {
        return 'RequestContainer';
    }
}
