<?php

declare(strict_types=1);


namespace Anng\lib;

use Anng\lib\db\Config;
use Anng\lib\db\Pool;
use Anng\lib\db\pool\Mysql;
use Anng\lib\db\Query;
use Anng\lib\contract\AppInterface;

class Db
{
    public Pool|null $pool = null;
    public $config;
    protected $sql;

    public function __construct(private AppInterface $app)
    {
    }

    public function create($class = Mysql::class)
    {
        if ($this->pool === null) {
            $this->pool = (new $class($this));
        }

        return $this;
    }

    public function name(string $name)
    {
        return (new Query($this->app))->name($name);
    }

    public function __call($method, $args = [])
    {
        $connect = $this->pool->get();
        $data = call_user_func_array([$connect, $method], $args);
        $this->pool->put($connect);
        return $data;
    }
}
