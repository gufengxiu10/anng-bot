<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Table as SwooleTable;

class Table
{
    private string $name;
    private array $columns;
    private array $tables;

    public function create($name,  $columns, $size = 1024)
    {
        $this->tables[$name] = new SwooleTable($size);
        foreach ($columns as $column) {
            $this->tables[$name]->column($column[0], $column[1], $column[2]);
        }
        $this->tables[$name]->create();
        $this->columns[$name] = $columns;
        return $this;
    }

    /**
     * @name: 
     * @param {*} $name
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-17 09:48:18
     * @return {*}
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-17 09:48:22
     * @return {*}
     */
    public function exists($name): bool
    {
        return isset($this->tables[$name]) ? true : false;
    }

    public function getinstance($name = '')
    {
        if (empty($name)) {
            return $this->tables;
        }
        return $this->tables[$name];
    }

    public function __call($method, $args)
    {
        $data = call_user_func_array([$this->tables[$this->name], $method], $args);
        $this->name = '';
        return $data;
    }
}
