<?php

declare(strict_types=1);


namespace Anng\lib;

use Anng\lib\db\Config;
use Anng\lib\db\Pool;
use Anng\lib\db\pool\Mysql;

class Db
{
    public Pool|null $pool = null;
    public $config;
    protected $sql;

    public function create($class = Mysql::class)
    {
        if ($this->pool === null) {
            $this->pool = (new $class($this));
        }

        $this->pool->get()->table('article')
            ->where('id', 10)
            ->whereOr(fn ($query) => $query->where('kid', 1))
            ->first();
        return $this;
    }

    /**
     * @name: 设置数据库信息
     * @param {*} string
     * @param {*} string
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 10:07:57
     * @return {*}
     */
    public function setConfig(string|array $key, string|null $val = null): static
    {
        if (!$this->config) {
            $this->config = new Config();
        }

        if (!is_null($val)) {
            $this->config->set($key, $val);
        } elseif (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->config->set($k, $v);
            }
        }
        return $this;
    }

    public function __call($method, $args = [])
    {
        $connect = $this->pool->get();
        $data = call_user_func_array([$connect, $method], $args);
        $this->pool->put($connect);
        return $data;
    }
}
