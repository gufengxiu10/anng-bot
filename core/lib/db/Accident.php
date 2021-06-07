<?php

declare(strict_types=1);


namespace Anng\lib\db;

use Anng\lib\facade\Cache;

class Accident
{
    private $tableFileInfo = [];

    public function __construct(public $connect, public $parse)
    {
    }

    public function getPk(): null|string
    {
        $info = $this->getField($this->parse->table());
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
                $statement = $this->connect->prepare($sql);
                if (!$statement) {
                    throw new \Exception('Prepare failed');
                }
                $result = $statement->execute();
                if (!$result) {
                    throw new \Exception('Execute failed');
                }

                $data = $statement->fetchAll();
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

    public function has($table, $field)
    {
        return isset($this->tableFileInfo[$table][$field]) ? true : false;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->connect, $method], $args);
    }
}
