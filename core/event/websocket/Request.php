<?php

namespace Anng\event\websocket;

use Anng\event\Request as EventRequest;
use Anng\lib\facade\App;
use Anng\lib\facade\Exeception;
use Anng\lib\facade\Request as FacadeRequest;
use Anng\lib\facade\Response as FacadeResponse;
use Anng\lib\facade\Route as FacadeRoute;
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
        try {
            FacadeRequest::send($request);
            FacadeResponse::send($response)
                ->end(FacadeRoute::send(App::get('request')))->clear();
            FacadeRequest::clear();
        } catch (Throwable $th) {
            FacadeResponse::end(Exeception::render($th));
        }
    }
}
