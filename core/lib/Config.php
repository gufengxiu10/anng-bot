<?php

declare(strict_types=1);


namespace Anng\lib;


class Config
{
    protected $config = [];

    public function load(string $file, string $name = '')
    {
        $config = [];
        if (is_file($file)) {
            $config = include $file;
        }

        if (!empty($name)) {
            $this->config[$name] = $config;
        } else {
            $this->config = array_merge($this->config, $config);
        }

        return $this->config;
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
