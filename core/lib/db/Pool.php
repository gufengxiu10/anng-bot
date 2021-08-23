<?php

declare(strict_types=1);

namespace Anng\lib\db;

use PDO;
use Swoole\Database\PDOPool;

abstract class Pool
{
    protected PDOPool $pool;

    public function __construct()
    {
    }

    abstract public function create();


    public function get()
    {
        $connect = $this->pool->get();
        if ($connect->getAttribute(PDO::ATTR_SERVER_INFO) === false) {
            $this->pool->put(null);
            $connect = null;
            return $this->get();
        }

        return $connect;
    }

    public function put($connect)
    {
    }
}
