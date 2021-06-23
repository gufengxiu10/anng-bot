<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Context;
use Anng\lib\Facade;
use Anng\lib\response\Json;

class ResponseJson extends Facade
{
    protected static function getFacadeClass()
    {
        $content = App::getInstance()->make('context');
        $response = $content->get('response');
        return new Json($response);
    }
}
