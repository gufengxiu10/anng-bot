<?php

declare(strict_types=1);

namespace Anng\lib\route;

use Anng\lib\exception\ResponseException;
use Anng\lib\facade\App;
use Anng\lib\facade\Reflection;

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

        [$controller, $action] = $route[2];
        if (!class_exists($controller)) {
            throw new ResponseException('控制器不存在');
        }

        $object = Reflection::instance($controller);
        if (!method_exists($object, $action)) {
            throw new ResponseException('方法不存在');
        }

        return $object->$action();
    }

    public function getRoute()
    {
        return array_filter($this->route->routes, function ($val) {
            return $this->request->isMethod($val[0]);
        });
    }

    public function perrlt()
    {
        $routes = $this->getRoute();
        $uri = ltrim($this->request->uri(), '/');
        foreach ($routes as $route) {
            $rule  = ltrim($route[1], '/');
            if ($rule == $uri) {
                return $route;
            }
        }
        return false;
    }
}
