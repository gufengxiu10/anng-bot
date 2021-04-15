<?php

declare(strict_types=1);

namespace Anng\lib\route;

use Anng\lib\exception\ResponseException;
use Anng\lib\facade\App;
use Anng\lib\facade\Reflection;
use ReflectionClass;

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

        $reflection = new ReflectionClass($controller);
        $args = [];
        if ($construct = $reflection->getConstructor()) {
            $args = Reflection::parseData($construct);
        }

        $controllerObject = $reflection->newInstanceArgs($args);
        if (!$reflection->hasMethod($action)) {
            throw new ResponseException('方法不存在');
        }

        $methodArgs = Reflection::parseData($reflection->getMethod($action), $route->getParam());
        $data = call_user_func_array([$controllerObject, $action], $methodArgs);

        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        return $data;
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
