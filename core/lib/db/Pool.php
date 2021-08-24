<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\contract\db\PoolInterface;
use Anng\lib\db\pool\Mysql;
use PDO;
use Swoole\Database\PDOPool;

class Pool implements PoolInterface
{
    protected $pool;

    public function __construct()
    {
    }

    public function create()
    {
        $this->pool = (new Mysql)->create();
    }

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
