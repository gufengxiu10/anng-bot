<?php

declare(strict_types=1);


namespace Anng\lib\db;

use Anng\lib\contract\db\Connect as ConnectInterface;
use Anng\lib\facade\Cache;
use Closure;
use PDO;
use PDOException;
use Swoole\Database\PDOProxy;


abstract class Connect implements ConnectInterface
{
    protected $build;
    public $tableFileInfo = [];

    public function __construct(protected PDOProxy $connect, protected Config $config)
    {
        $this->build = new Biluder($this);
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
        $result = $this->send($query, fn () => $this->build->get($query, $one))->fetchAll(PDO::FETCH_ASSOC);;
        if ($one === true) {
            return Collection::make($result[0] ?? []);
        }

        return $result;
    }

    /**
     * @name: 判断记录是否存在
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function exists(Query $query)
    {
        $sql = $this->build->exists($query);
        try {
            $statement = $this->connect->prepare($sql);
            $result = $statement->execute();
            if (!$result) {
                throw new \Exception('Execute failed');
            }
            return $statement->rowCount();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function insert(Query $query)
    {
        $this->send($query, fn () => $this->build->insert($query));
        return $this->getLastInsertId();
    }

    public function update(Query $query)
    {
        dump($this->build->update($query));
        $this->send($query, fn () => $this->build->update($query));
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

            return $statement;
        } catch (PDOException $th) {
            //throw $th;
            dump($th->getMessage() . '|' . $sql);
            throw new \Exception($th->getMessage());
        }
    }

    public function getPk(string $table): null|string
    {
        $info = $this->getField($table);
        foreach ($info as $key => $value) {
            if ($value['key'] == true) {
                return $key;
            }
        }
        return null;
    }

    /**
     * @name: 获得表字段信息
     * @param {*} $table
     * @author: ANNG
     * @return {*}
     */
    public function getField($table): array
    {
        if (!isset($this->tableFileInfo[$table])) {
            if (Cache::has("{$table}File", 'mysql')) {
                $this->tableFileInfo[$table] = Cache::get("{$table}File", null, 'mysql');
            } else {
                $sql = 'SHOW FULL COLUMNS FROM ' . $table;
                $data = $this->send($sql)->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $val) {
                    $this->tableFileInfo[$table][$val['Field']] = [
                        'type'      => $val['Type'],
                        'key'       => $val['Key'] == 'PRI',
                        'comment'   => $val['Comment']
                    ];
                }

                Cache::set("{$table}File", $this->tableFileInfo[$table], 3600, 'mysql');
            }
        }

        return $this->tableFileInfo[$table];
    }

    public function getLastInsertId()
    {
        return $this->connect->lastInsertId();
    }
}
