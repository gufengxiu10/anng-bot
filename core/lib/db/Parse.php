<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\contract\db\QueryInterface;

class Parse
{
    public function __construct(private QueryInterface $query)
    {
        # code...
    }


    public function select()
    {
        $sql = 'SELECT * ';
        $sql .= $this->table();
        $sql .= ' WHERE ' . $this->where();
        return $sql;
    }

    private function table()
    {
        $table = $this->query->getOption('table');
        dump($this->query->getOption('alias'));
        if (!is_null($this->query->getOption('alias'))) {
            $table . ' as ' . $this->query->getOption('alias');
        }

        return $table;
    }


    private function where()
    {
        $where = '';
        foreach ((array)$this->query->getOption('where') as $value) {
            if (is_array($value[0])) {
                foreach ($value as $v) {
                }
            } else {
                if (is_null($value[2])) {
                    $where .= implode(' ', [$value[3], $value[0], '=', $value[1]]);
                } else {
                    $where .= implode(' ', [$value[3], $value[0], $value[1]], $value[2]);
                }
            }
        }

        return ltrim($where, 'AND');
    }
}
