<?php

declare(strict_types=1);

namespace Anng\lib\db;

class Biluder
{
    protected $selectSql = "SELECT %FIELD% FROM %TABLE% %WHERE% %LIMIT%";

    public function get(Query $query, $one)
    {
        return str_replace(["%TABLE%", "%FIELD%", "%WHERE%", "%LIMIT%"], [
            $this->parseTable($query),
            $this->parseField($query),
            !empty($where = $this->parseWhere($query)) ? "WHERE " . $where : '',
            $one ?  "LIMIT 1" : $this->parseLimit($query)
        ], $this->selectSql);
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
                    $t[] .= "{$logic} {$value[0]} {$value[1]} " . (is_int($value[2]) ? $value[2] : "'{$value[2]}'");
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
