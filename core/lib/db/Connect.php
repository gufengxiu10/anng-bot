<?php

declare(strict_types=1);


namespace Anng\lib\db;

use Anng\lib\contract\db\Connect as ConnectInterface;
use Closure;
use PDO;
use PDOException;
use Swoole\Database\PDOProxy;


abstract class Connect implements ConnectInterface
{
    protected $build;
    public $accident;

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
        return $this->query()->table($table);
    }

    public function name(string $table): Query
    {
        return $this->query()->name($table);
    }

    public function get($query, bool $one = false)
    {
        $result = $this->send($query, fn () => $this->build->get($query, $one));
        if ($one === true) {
            return Collection::make($result[0] ?? []);
        }

        return $result;
    }

    public function send($query, $sql = null)
    {
        if (!is_null($sql) && $sql instanceof Closure) {
            $sql = $sql($query);
        } else {
            $sql = $query;
        }
        dump($sql);
        try {
            $statement = $this->connect->prepare($sql);
            $result = $statement->execute();
            if (!$result) {
                throw new \Exception('Execute failed');
            }

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            //throw $th;
            dump($th->getMessage());
        }
    }
}
