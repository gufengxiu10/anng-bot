<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Closure;
use PDO;
use PDOException;

class Connect
{
    private $connect;

    public function __construct(private Pool $pool)
    {
        $this->get();
    }

    private function get()
    {
        if (!$this->connect) {
            $this->connect = $this->pool->get();
        }

        return $this->connect;
    }

    public function send($query, $sql = null)
    {
        if (!is_null($sql) && $sql instanceof Closure) {
            $sql = $sql($query);
        } else {
            $sql = $query;
        }

        try {
            $statement = $this->connect->prepare($sql);
            $result = $statement->execute();
            if (!$result) {
                throw new \Exception('Execute failed');
            }

            return $statement;
        } catch (PDOException $th) {
            //throw $th;
            dump($th->getMessage() . '|' . $sql);
            throw new \Exception($th->getMessage());
        }
    }


    public function getAll($query, $sql = null)
    {
        $statement = $this->send($query, $sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne()
    {
        # code...
    }

    public function __destruct()
    {
        $this->pool->put($this->connect);
        dump(10);
    }
}
