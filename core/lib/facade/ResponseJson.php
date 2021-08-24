<?php

declare(strict_types=1);

namespace Anng\lib\facade;

use Anng\lib\Context;
use Anng\lib\contract\ContextInterface;
use Anng\lib\Facade;
use Anng\lib\response\Json;
use Anng\utils\ApplicationContext;

class ResponseJson extends Facade
{
    protected static function getFacadeClass()
    {
        $response = ApplicationContext::get(ContextInterface::class)->get('response');
        return new Json($response);
    }
}
