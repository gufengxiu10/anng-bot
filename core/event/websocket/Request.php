<?php

namespace Anng\event\websocket;

use Anng\event\Request as EventRequest;
use Anng\lib\Context;
use Anng\lib\facade\App;
use Anng\lib\facade\Exeception;
use Anng\lib\facade\RequestContainer;
use Anng\lib\facade\ResponseJson;
use Anng\lib\facade\Route as FacadeRoute;
use Anng\lib\Request as LibRequest;
use Anng\lib\Response as LibResponse;
use Swoole\Coroutine;
use Swoole\Http\Request as HttpRequest;
use Swoole\Http\Response;
use Throwable;

class Request extends EventRequest
{
    protected static $id;
    /**
     * @name: websocket服务
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function run(HttpRequest $request, Response $response)
    {
        $context = App::getInstance()->make(Context::class);
        parent::run($request, $response);
        $context->push('request', (new LibRequest($request)));
        $context->push('response', $response);

        try {
            FacadeRoute::send($context->get('request'));
        } catch (Throwable $th) {
            ResponseJson::sendData($th)->end(Exeception::render($th));
        }
        $context->delete();
    }
}
