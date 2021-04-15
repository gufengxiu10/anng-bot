<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\Db;
use Anng\lib\db\biluder\Mysql;
use Anng\lib\db\biluder\sql\Conditions;
use PDO;
use Swoole\Database\PDOProxy;

class Sql
{
    use Conditions;

    protected PDOProxy $baseConnection;
    protected $connection;
    protected $pool;
    protected $biluder;
    protected $parse;

    public array $data = [];

    private bool $isSql = false;

    public function __construct(Db $db, Config $config)
    {
        $this->db = $db;
        $this->baseConnection = $db->getPool()->get();
        $this->parse = new Parse();
        $this->biluder = new Mysql($this->parse);
        $this->connection = new Connection($this->baseConnection, $this->parse);
        $this->config = $config;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @name: 添加
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-29 11:02:52
     * @return {*}
     */
    public  function insert(array $data)
    {
        $this->data = $data;
        $sql = $this->biluder->insert();

        $pk = $this->connection->getPk();
        if ($this->isSql === true) {
            return $sql;
        }
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        $id = $this->connection->lastInsertId();
        return array_merge($data, [$pk => $id]);
    }

    /**
     * @name: 添加并获得ID
     * @param {*} $data
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 10:22:18
     * @return {*}
     */
    public function insertId($data)
    {
        $this->data = $data;
        $sql = $this->biluder->insert();
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        $id = $this->connection->lastInsertId();
        return $id;
    }

    /**
     * @name: 批量添加
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 11:01:00
     * @return {*}
     */
    public function insertAll(array $data)
    {
        $this->data = $data;
        $sql = $this->biluder->insertAll();
        if ($this->isSql === true) {
            return $sql;
        }
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }
        return $result;
    }

    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-01 09:48:45
     * @return {*}
     */
    public function find(int|string $id = null)
    {
        if (!empty($id)) {
            $this->where($this->connection->getPk(), (int)$id);
        }

        $sql = $this->biluder->find();
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        $data = $statement->fetch(PDO::FETCH_ASSOC);
        return $data;
    }

    public function select()
    {
        $sql = $this->biluder->select();
        if ($this->isSql === true) {
            return $sql;
        }
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getSql(bool $bool = false): static
    {
        $this->isSql = $bool;
        return $this;
    }

    private function clear()
    {
        $this->where = [];
        $this->table = null;
        $this->field = '*';
        $this->alias = null;
        $this->data = [];
    }

    public function __destruct()
    {
        $this->clear();
        $this->biluder = null;
        $this->parse = null;
        $this->connection = null;
        $this->db->getPool()->put($this->baseConnection);
    }
}
