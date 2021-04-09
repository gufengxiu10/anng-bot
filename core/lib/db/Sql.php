<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\Db;
use Anng\lib\db\biluder\Mysql;
use PDO;
use Swoole\Database\PDOProxy;

class Sql
{
    protected PDOProxy $baseConnection;
    protected $connection;
    protected $pool;
    protected $biluder;
    protected $parse;


    //表名
    public string|null $table = null;

    //字段
    public string|array $field = '*';

    //别名
    public string|null $alias = null;

    //条件
    public array $where = [];

    public string|array|null|int $limit = null;

    public array $data = [];

    public $offset;

    private bool $isSql = false;

    public function __construct(Db $db, Config $config)
    {
        $this->db = $db;
        $this->baseConnection = $db->getPool()->get();
        $this->biluder = new Mysql();
        $this->parse = new Parse();
        $this->connection = new Connection($this->baseConnection, $this->parse);
        $this->config = $config;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @name: 设置表
     * @param {*}
     * @author: ANNG
     * @Date: 2021-01-28 10:41:08
     * @return static
     */
    public function name($val): static
    {
        $this->table = is_null($this->config->get('prefix')) ? $val :  $this->config->get('prefix') . $val;
        return $this;
    }

    /**
     * @name: 设置查询字段
     * @param string|array|bool $val 字段值
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 13:47:17
     * @return {*}
     */
    public function field(string|array|bool $val): static
    {
        if ($val === false) {
            $val = '*';
        }
        $this->field = $val;
        return $this;
    }

    /**
     * @name: 别名
     * @param string $val 别名值
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 13:51:43
     * @return {*}
     */
    public function alias(string $val): static
    {
        $this->alias = $val;
        return $this;
    }

    /**
     * @name: 条件
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:10:22
     * @return {*}
     */
    public function where($field, $condition, $value = null)
    {
        $where = [$field, $condition, $value];
        array_push($this->where, $where);
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
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
        $sql = $this->parse()->insert();

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
        $sql = $this->parse()->insert();
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
        $sql = $this->parse()->insertAll();
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
    public function find()
    {
        $sql = $this->parse()->find();
        $statement = $this->connection->prepare($sql);
        if (!$statement) {
            throw new \Exception('Prepare failed');
        }
        $result = $statement->execute();
        if (!$result) {
            throw new \Exception('Execute failed');
        }

        $data = $statement->fetch();
        return $data;
    }

    public function select()
    {
        $sql = $this->parse()->select();
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

    private  function parse()
    {
        $this->parse->setData([
            'where' => $this->where,
            'table' => $this->table,
            'field' => $this->field,
            'alias' => $this->alias,
            'data'  => $this->data,
        ]);

        $this->biluder->setParse($this->parse);
        return $this->biluder;
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
