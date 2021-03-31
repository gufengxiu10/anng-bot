<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\facade\App;
use Anng\lib\route\Dispatch;
use Symfony\Component\Finder\Finder;

class Route
{
    private $request;
    public array $routes = [];

    public function send(Request $request)
    {
        $this->request = $request;
        $this->load();
        return $this->dispatch();
    }

    public function dispatch()
    {
        return (new Dispatch())->send($this->request, $this)
            ->run();
    }

    /**
     * @name: 路由文件加载
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-22 09:39:16
     * @return {*}
     */
    public function load()
    {
        $path = App::getRoutePath();
        if (is_dir($path)) {
            $finder = new Finder();
            $finder->in(App::getRoutePath())->name('*.php');
            foreach ($finder->files() as $file) {
                include_once $file;
            }
        }
    }

    public function get($rule, $route = '')
    {
        $this->addGroup($rule, $route, 'get');
        return $this;
    }

    public function post($rule, $route = '')
    {
        $this->addGroup($rule, $route, 'post');
        return $this;
    }

    public function addGroup($rule, $route = '', $method = '*')
    {
        $this->routes[] = [$method, $rule, $route];
        return $this;
    }
}
