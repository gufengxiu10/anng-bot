<?php

declare(strict_types=1);

namespace Anng\lib\route;

use Anng\lib\Collection;
use Anng\lib\contract\response\Response;
use Anng\lib\exception\ResponseException;
use Anng\lib\facade\App;
use Anng\lib\facade\Reflection;
use Anng\lib\facade\ResponseJson;
use Anng\lib\reflection\ReflectionClass;

class Dispatch
{
    private $request;
    private $route;

    public function send($request, $route)
    {
        $this->request = $request;
        $this->route = $route;
        return $this;
    }

    public function run()
    {
        $route = $this->perrlt();
        if ($route === false) {
            throw new ResponseException('路由不存在');
        }

        [$controller, $action] = $route->getClass();
        if (!class_exists($controller)) {
            throw new ResponseException('控制器不存在');
        }

        $refltion = (new ReflectionClass($controller));

        if (!$refltion->getRefltion()->hasMethod($action)) {
            throw new ResponseException('方法不存在');
        }

        $data = $refltion->sendMethod($action, $route->getParam()) ?: '';
        if ($data instanceof Response) {
            $data->end();
        } else {
            ResponseJson::sendData($data)->end();
        }
    }

    public function perrlt()
    {
        foreach ($this->route->routes as $item) {
            if ($item->checkMethod($this->request->method()) && $item->checkRule($this->request->uri())) {
                return $item;
            }
        }
        return false;
    }
}
