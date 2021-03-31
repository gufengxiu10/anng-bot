<?php

declare(strict_types=1);

namespace Anng\lib\db\biluder\sql;

use Predis\Command\KeySort;

trait Insert
{
    protected $installSql = "INSERT INTO %TABLE%(%FIELD%) VALUES %DATA% %COMMENT%";
    protected $installAllSql = "INSERT INTO %TABLE%(%FIELD%) %DATA% %COMMENT%";

    public function insert()
    {
        $data = $this->parse->data();
        if (empty($data)) {
            return false;
        }

        $field = array_keys($data);
        $values = array_values($data);

        $sql = str_replace(["%TABLE%", "%FIELD%", "%DATA%", "%COMMENT%"], [
            $this->parse->table(),
            implode(',', $field),
            '(' . implode(',', $values) . ')',
            ''
        ], $this->installSql);

        return $sql;
    }

    public function insertAll()
    {
        $data = $this->parse->data([], false);
        if (empty($data)) {
            return false;
        }

        $value = [];

        foreach ($data as &$val) {
            $val = $this->parse->data($val);
            ksort($val);
            array_push($value, "(" . implode(',', $val) . ')');
        }

        $field = array_keys(end($data));
        $sql = str_replace(["%TABLE%", "%FIELD%", "%DATA%", "%COMMENT%"], [
            $this->parse->table(),
            implode(',', $field),
            implode(',', $value),
            ''
        ], $this->installSql);
        return $sql;
    }
}
