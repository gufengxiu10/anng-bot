<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\facade\App;
use Anng\lib\route\Dispatch;
use Anng\lib\route\Group;
use Anng\lib\route\RuleItem;
use Symfony\Component\Finder\Finder;

class Route
{
    private $request;
    public array $routes = [];
    private $group;

    public function send(Request $request)
    {
        $this->request = $request;
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
        $this->defaultGroup();

        $path = App::getRoutePath();
        if (is_dir($path)) {
            $finder = new Finder();
            $finder->in(App::getRoutePath())->name('*.php');
            foreach ($finder->files() as $file) {
                include_once $file;
            }
        }
    }

    private function defaultGroup()
    {
        $this->group = new Group($this);
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @name: 路由分组
     * @param {*} $rule
     * @param {callable} $done
     * @author: ANNG
     * @return {*}
     */
    public function group($rule, callable $done)
    {
        new Group($this, $this->group, $rule, $done);
    }

    public function get($rule, $route = '')
    {
        $this->group->setRule($rule, $route, 'get');
        return $this;
    }

    public function post($rule, $route = '')
    {
        $this->group->setRule($rule, $route, 'post');
        return $this;
    }

    public function put($rule, $route = '')
    {
        $this->group->setRule($rule, $route, 'put');
        return $this;
    }

    public function delete($rule, $route = '')
    {
        $this->group->setRule($rule, $route, 'delete');
        return $this;
    }

    public function addRoutes(RuleItem $item)
    {
        $this->routes[] = $item;
    }
}
