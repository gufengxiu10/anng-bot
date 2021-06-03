<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Facade;
use Anng\lib\response\Json;

class ResponseJson extends Facade
{
    protected static function getFacadeClass()
    {
        $response = RequestContainer::get('response');
        return new Json($response);
    }
}
