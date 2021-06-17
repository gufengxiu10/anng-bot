<?php

declare(strict_types=1);

namespace Anng\lib\db;

class Biluder
{
    protected $selectSql = "SELECT %FIELD% FROM %TABLE% %WHERE% %LIMIT%";

    public function get(Query $query, $one)
    {
        dump(!empty($where = $this->parseWhere($query)) ? "WHERE " . ltrim(implode('', $where), 'AND') : []);
        // return str_replace(["%TABLE%", "%FIELD%", "%WHERE%", "%LIMIT%"], [
        //     $this->parseTable($query),
        //     $this->parseField($query),
        //     !empty($where = $this->parseWhere($query)) ? "WHERE " . ltrim(ltrim(implode(' ', $where), 'AND'), 'OR') : [],
        //     $one ? $this->parseLimit($query) : 1
        // ], $this->selectSql);
    }

    public function parseTable(Query $query)
    {
        return ($alias = $query->getOption('alias')) ? $alias . $query->getOption('table') : $query->getOption('table');
    }

    public function parseField(Query $query)
    {
        return $query->getOption('field');
    }

    public function parseWhere(Query $query)
    {
        $where = $query->getOption('where');

        $sql = '';
        $t = [];
        foreach ($where as $key => $item) {
            foreach ($item as $value) {
                if ($value instanceof Query) {
                    $t = array_merge($t, $this->parseWhere($value));
                } else {
                    if (is_null($value[2])) {
                        $t[] = " {$key} {$value[0]} = {$value[1]}";
                    } else {
                        $sql .= "{$value[0]} {$value[1]} " . (is_int($value[1]) ? $value[1] : "'{$value[1]}'");
                    }
                }
            }
        }
        dump($t);
        return $t;
    }


    public function parseLogic()
    {
        # code...
    }

    private function parseLimit(Query $query)
    {
        # code...
    }
}
