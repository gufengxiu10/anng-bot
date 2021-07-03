<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\contract\db\Connect;

class Biluder
{
    protected $selectSql = "SELECT %FIELD% FROM %TABLE% %WHERE% %LIMIT%";
    protected $existsSql = "SELECT 1 FROM %TABLE% %WHERE% LIMIT 1";
    protected $insertSql = "INSERT INTO %TABLE% VALUES %DATA%";
    protected $updateSql = "UPDATE %TABLE% SET %DATA% %WHERE%";

    public function __construct(protected Connect $connect)
    {
        # code...
    }

    public function get(Query $query, $one)
    {
        return str_replace(["%TABLE%", "%FIELD%", "%WHERE%", "%LIMIT%"], [
            $this->parseTable($query),
            $this->parseField($query),
            !empty($where = $this->parseWhere($query)) ? "WHERE " . $where : '',
            $one ?  "LIMIT 1" : $this->parseLimit($query)
        ], $this->selectSql);
    }

    public function exists(Query $query)
    {
        return str_replace(["%TABLE%", "%WHERE%"], [
            $this->parseTable($query),
            !empty($where = $this->parseWhere($query)) ? "WHERE " . $where : ''
        ], $this->existsSql);
    }

    public function insert(Query $query)
    {
        $data = $query->getOption('data');
        if (empty($data)) {
            return 'not DATA';
        }

        $field = array_keys($data);
        $values = array_values($data);
        $table = $this->parseTable($query);
        $table .= '(' . implode(',', array_map(fn ($item) => "`{$item}`", $field)) . ')';
        $values = '(' . implode(',', array_map(fn ($item) => is_numeric($item) ? $item : "'{$item}'", $values)) . ')';
        return str_replace(["%TABLE%", "%DATA%"], [
            $table,
            $values
        ], $this->insertSql);
    }

    public function update(Query $query)
    {
        $data = $query->getOption('data');
        if (empty($data)) {
            return 'not DATA';
        }

        $field = $this->connect->getField($query->getOption('table'));
        foreach ($data as $key => &$item) {
            if (!isset($field[$key])) {
                unset($data[$key]);
                continue;
            }

            $item = "`{$key}` = " . (is_numeric($item) ? $item : "'{$item}'");
        }


        $data = array_intersect_key($data, $field);

        $where = $this->parseWhere($query);
        if (empty($where)) {
            return 'where not condition';
        }

        return str_replace(["%TABLE%", "%DATA%", "%WHERE%"], [
            $this->parseTable($query),
            implode(',', $data),
            "WHERE " . $where,
        ], $this->updateSql);
    }

    public function parseTable(Query $query)
    {
        return ($alias = $query->getOption('alias')) ? $alias . $query->getOption('table') : $query->getOption('table');
    }

    public function parseField(Query $query)
    {
        $field = $query->getOption('select');
        if (empty($field)) {
            return '*';
        }

        $field = array_map(fn ($item) => "`{$item}`", $field);
        return implode(',', $field);
    }

    public function parseWhere(Query $query)
    {
        $where = $query->getOption('where');
        if (empty($where)) {
            return '';
        }

        $sql = '';
        foreach ($where as $logic => $item) {
            $sql .= $this->parseLogic($logic, $item);
        }
        return ltrim(ltrim($sql), array_key_first($where));
    }


    public function parseLogic($logic, $item)
    {
        $t = [];
        foreach ($item as $value) {
            if ($value instanceof Query) {
                if (!empty($k = $this->parseWhere($value))) {
                    $t[] = ' ' . $logic . ' ( ' . $k . ' )';
                }
            } else {
                if (is_null($value[2])) {
                    $t[] = "{$logic} {$value[0]} = {$value[1]}";
                } else {
                    if (in_array($value[1], ['in', 'not in'])) {
                        if (is_array($value[2])) {
                            $value[2] = implode(',', $value[2]);
                        }
                        $t[] .= "{$logic} {$value[0]} in ({$value[2]}) ";
                    } else {
                        $t[] .= "{$logic} {$value[0]} {$value[1]} " . (is_int($value[2]) ? $value[2] : "'{$value[2]}'");
                    }
                }
            }
        }

        return ltrim(implode(' ', $t), $logic);
    }

    private function parseLimit(Query $query)
    {
        $limit = $query->getOption('limit');
        if (empty($limit)) {
            return '';
        }

        [$start, $end] = $limit;
        $sql = 'LIMIT ';
        if (is_null($end)) {
            $sql .= $start;
        } else {
            $sql .= "{$start},{$end}";
        }
        return $sql;
    }
}
