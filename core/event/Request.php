<?php

namespace Anng\event;

use Anng\lib\facade\Container;
use Anng\lib\facade\Route as FacadeRoute;
use Anng\lib\Request as LibRequest;
use Anng\lib\Route;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response;

class Request
{
    public function run(HttpRequest $request, Response $response)
    {
        $request = new LibRequest($request);
        $data = FacadeRoute::send($request);
        $response->end($data);
    }
}
