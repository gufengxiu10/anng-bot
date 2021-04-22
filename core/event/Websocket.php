<?php

namespace Anng\event;

use Anng\lib\facade\Container;
use Anng\lib\facade\Exeception;
use Anng\lib\facade\Request as FacadeRequest;
use Anng\lib\facade\Response as FacadeResponse;
use Anng\lib\facade\Route as FacadeRoute;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response;
use Throwable;

class Websocket
{
    /**
     * @name: websocket服务
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function message()
    {
    }

    /**
     * @name: http服务
     * @param {HttpRequest} $request
     * @param {Response} $response
     * @author: ANNG
     * @return {*}
     */
    public function request(HttpRequest $request, Response $response)
    {
        try {
            FacadeRequest::send($request);
            FacadeResponse::send($response)->end(FacadeRoute::send(Container::get('request')))->clear();
            FacadeRequest::clear();
        } catch (Throwable $th) {
            FacadeResponse::end(Exeception::render($th));
        }
    }
}
