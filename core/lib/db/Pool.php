<?php

declare(strict_types=1);


namespace Anng\lib\db;

use Anng\lib\contract\db\Connect;
use Anng\lib\db\connect\Mysql;
use Swoole\Database\PDOPool;

abstract class Pool
{
    protected PDOPool $pool;
    protected Config $config;
    public function __construct(protected $db)
    {
        $this->config = $this->db->config;
        $this->create();
    }

    abstract public function create();


    public function get(): Connect
    {
        return new Mysql($this->pool->get(), $this->db->config);
    }

    public function put(Mysql $connect)
    {
        return $this->pool->put($connect->connect());
    }
}
