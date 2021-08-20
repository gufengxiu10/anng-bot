<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Context;
use Anng\lib\Facade;

class Response extends Facade
{
    protected static function getFacadeClass()
    {
        $context = App::getInstance()->make(Context::class);
        return $context->get('response');
    }
}
