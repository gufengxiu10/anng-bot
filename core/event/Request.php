<?php

namespace Anng\event;

use Anng\lib\facade\Container;
use Anng\lib\Route;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response;

class Request
{
    public function run(HttpRequest $request, Response $response)
    {
        Container::bind('request', $request);
        Container::bind('response', $response);

        (new Route)->run();

        Container::clear('request', $request);
        Container::clear('response', $response);
    }
}
