<?php

namespace Anng\event\websocket;

use Anng\event\Request as EventRequest;
use Anng\lib\facade\Exeception;
use Anng\lib\facade\RequestContainer;
use Anng\lib\facade\Route as FacadeRoute;
use Anng\lib\Request as LibRequest;
use Anng\lib\Response as LibResponse;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response;
use Throwable;

class Request extends EventRequest
{
    /**
     * @name: websocket服务
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function run(HttpRequest $request, Response $response)
    {
        parent::run($request, $response);
        RequestContainer::set('request', (new LibRequest)->send($request));
        RequestContainer::set('response', (new LibResponse())->send($response));
        try {
            RequestContainer::get('response')->end(FacadeRoute::send(RequestContainer::get('request')));
        } catch (Throwable $th) {
            RequestContainer::get('response')->end(Exeception::render($th));
        }
        RequestContainer::clear();
    }
}
