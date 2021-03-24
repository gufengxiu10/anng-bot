<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;
use Anng\lib\Response as ResponseBase;

class Response extends Facade
{
    protected static function getFacadeClass()
    {
        return ResponseBase::class;
    }
}
