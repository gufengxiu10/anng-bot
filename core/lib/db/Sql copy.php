<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\db\Collection;
use Anng\lib\Db;
use Anng\lib\db\biluder\Mysql;
use Anng\lib\db\biluder\sql\Conditions;
use Anng\lib\exception\DbException;
use PDO;
use Swoole\Database\PDOProxy;
use Swoole\Database\PDOStatementProxy;

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
        $this->connection = new Accident($this->baseConnection, $this->parse);
        $this->config = $config;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function update(array $data)
    {

        if ($this->parse->isEmpty('where')) {
            throw new DbException('至少需要一个更新条件');
        }

        $pk = $this->connection->getPk();
        if (array_key_exists($pk, $data)) {
            $this->where($pk, $data[$pk]);
            unset($data[$this->connection->getPk()]);
        }
        $tableField = $this->connection->getField($this->parse->table());
        $data = array_intersect_key($data, $tableField);
        $this->parse->set = $data;
        $sql = $this->biluder->update();
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

        return $statement->rowCount();
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
        $this->parse->data = $data;
        $sql = $this->biluder->insert();
        $pk = $this->connection->getPk();
        if ($this->isSql === true) {
            return $sql;
        }
        $this->sendSql($sql);
        $id = $this->connection->lastInsertId();
        return Collection::make(array_merge($data, [$pk => $id]));
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
        $this->parse->data = $data;
        $sql = $this->biluder->insert();
        $this->sendSql($sql);
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
        $this->parse->data = $data;
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
        return Collection::make($data);
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

        $list = $statement->fetchAll(PDO::FETCH_ASSOC);
        return Collection::make($list);
    }

    public function count()
    {
        $sql = $this->biluder->count();
        if ($this->isSql === true) return $sql;
        $statement = $this->sendSql($sql);
        $list = $statement->fetch(PDO::FETCH_ASSOC);
        return array_shift($list);
    }

    private function sendSql($sql): PDOStatementProxy
    {
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        return $statement;
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