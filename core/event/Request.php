<?php

namespace Anng\event;

use Anng\lib\facade\Container;
use Anng\lib\facade\Request as FacadeRequest;
use Anng\lib\facade\Response as FacadeResponse;
use Anng\lib\facade\Route as FacadeRoute;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response;

class Request
{
    public function run(HttpRequest $request, Response $response)
    {
        // TODO::此处参数注入感觉存在BUG,在并发的时候感觉会出问题
        FacadeRequest::send($request);
        FacadeResponse::send($response)->end(FacadeRoute::send(Container::get('request')))->clear();
        FacadeRequest::clear();
    }
}
