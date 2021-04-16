<?php

declare(strict_types=1);

namespace Anng\lib\db;

class Parse
{
    //表名
    public string|null $table = null;

    //字段
    public string|array $field = '*';

    //别名
    public string|null $alias = null;

    //条件
    public array $where = [];

    public string|array|null|int $limit = null;

    public $offset;

    public $connection = [];


    public function setData($data)
    {
        $this->connection = $data;
    }

    /**
     * @name: field字段
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 17:27:28
     * @return {*}
     */
    public function field()
    {
        return $this->field;
    }


    /**
     * @name: 表名分析
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 17:29:29
     * @return {*}
     */
    public function table()
    {
        $table = $this->table;
        if ($this->alias) {
            $table .= ' AS ' . $this->alias;
        }

        return $table;
    }

    public function set()
    {
        // dump($this->set);
        $sql = [];
        foreach ($this->set as $k => $v) {
            $sql[] = "`{$k}` = " . (is_int($v) ? $v : "'{$v}'");
        }
        return implode(',', $sql);
    }

    /**
     * @name: where语句分析
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 17:30:08
     * @return {*}
     */
    public function where()
    {
        $where = $this->where ?: [];
        if (empty($where)) {
            return '';
        }
        $sql = "WHERE ";
        foreach ($where as $value) {
            if (isset($value[2])) {
                if (in_array($value[1], ['<', '>', '<>', '='])) {
                    $sql .= "(" . implode(' ', $value) . ") AND ";
                } elseif (strtolower($value[1]) == 'like') {
                    $v = is_string($value[1]) ? "'" .  $value[2] . "'" : $value[1];
                    $sql .= "(`{$value[0]}` LIKE {$v}) AND ";
                }
            } elseif (!isset($value[2])) {
                $v = is_string($value[1]) ? "'" .  $value[1] . "'" : $value[1];
                $sql .= "(`{$value[0]}` = {$v}) AND ";
            }
        }

        return rtrim($sql, 'AND ');
    }

    /**
     * @name: 数据分析
     * @param {*} $data
     * @param {*} $handle
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 17:30:54
     * @return {*}
     */
    public function data($data = [], $handle = true)
    {
        $data = $data ?: $this->data ?: [];
        $re = [];
        if ($handle === true) {
            foreach ($data as $key => $value) {
                if (!is_scalar($value)) {
                    $val = json_encode($value);
                } elseif (is_string($value)) {
                    $val = "'" . trim(addslashes($value), '"') . "'";
                } else {
                    $val = $value;
                }

                $re['`' . $key . '`'] = $val;
            }
        } else {
            $re = $data;
        }


        return $re;
    }

    public function limit()
    {
        if (empty($this->limit)) {
            return '';
        }

        return 'limit ' . $this->limit;
    }

    public function offset()
    {
        $offset = $this->offset ?? '';
        if (empty($offset)) {
            return '';
        }
    }

    public function isEmpty($condition)
    {
        return empty($this->$condition) ? true : false;
    }
}
