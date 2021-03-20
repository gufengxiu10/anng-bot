<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\facade\App;
use Anng\lib\facade\Container;
use Anng\lib\route\Dispatch;
use Symfony\Component\Finder\Finder;

class Route
{


    /**
     * @name: 初始化
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-20 14:23:21
     * @return {*}
     */
    public function run($request)
    {
        $this->request = $request;
        $this->load();
        (new Dispatch)->send($request);
    }

    public function load()
    {
        $finder = new Finder();
        $finder->in(App::getRoutePath())->name('*.php');
        foreach ($finder->files as $file) {
            include_once $file;
        }
    }
}
