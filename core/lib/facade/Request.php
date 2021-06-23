<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\contract\RequestInterface;
use Anng\lib\Facade;

class Request extends Facade implements RequestInterface
{
    protected static function getFacadeClass()
    {
        $context = App::getInstance()->make('context');
        return $context->get('request');
    }
}
