<?php

declare(strict_types=1);


namespace Anng\lib\db;

use Anng\lib\contract\db\Connect as ConnectInterface;
use Closure;
use Swoole\Database\PDOProxy;


abstract class Connect implements ConnectInterface
{
    protected $build;

    public function __construct(protected PDOProxy $connect, protected Config $config)
    {
        $this->build = new Biluder;
    }

    public function connect()
    {
        return $this->connect;
    }

    public function getConfig($name)
    {
        return $this->config->$name ?? '';
    }

    public function query()
    {
        return new Query($this);
    }

    public function table(string $table): Query
    {
        dump(10);
        return $this->query()->table($table);
    }


    public function get($query, bool $one = false)
    {
        $this->send($query, fn () => $this->build->get($query, $one));
    }

    protected function send($query, $sql)
    {
        if ($sql instanceof Closure) {
            $sql = $sql($query);
        }
    }
}
