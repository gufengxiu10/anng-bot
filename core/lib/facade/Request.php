<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;
use Anng\lib\Request as RequestBase;

class Request extends Facade
{
    protected static function getFacadeClass()
    {
        return RequestBase::class;
    }
}
