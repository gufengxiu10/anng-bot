<?php

declare(strict_types=1);

namespace Anng\lib\route;

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
        if (isset($this->route->routes[$this->request->method()])) {
            dump($this->route->routes[$this->request->method()]);
        }
    }
}
