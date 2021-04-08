<?php

declare(strict_types=1);


namespace Anng\lib;

use Anng\lib\facade\App;
use Symfony\Component\Finder\Finder;

class Config
{
    protected $config = [];

    public function load(): void
    {
        $finder = new Finder;
        $finder->in(App::getConfigPath())->name('*.php');
        foreach ($finder->files() as $file) {
            $config = include_once $file;
            $key = substr($file->getFilename(), 0, strpos($file->getFilename(), '.'));
            $this->config = array_merge($this->config, [$key => $config]);
        }
    }

    /**
     * @name: 获得参数
     * @param {*} $name
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-19 09:40:25
     * @return {*}
     */
    public function get($name = '')
    {
        if (empty($name)) {
            return $this->config;
        }

        if (str_contains($name, '.')) {
            $name = explode('.', $name);
            return $this->config[$name[0]][$name[1]];
        }
        return $this->config[$name];
    }

    public function __get($name)
    {
        return $this->get($name);
    }
}
