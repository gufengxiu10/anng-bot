<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\db\concern\WhereQuery;

class Query
{
    use WhereQuery;

    protected array $option = [];
    protected string $prefix;

    public function __construct(protected Connect $connect)
    {
        $this->prefix = $this->connect->getConfig('prefix');
        $this->accident = new Accident($connect, $this);
    }

    public function getOption($name = '')
    {
        if (empty($name)) {
            return $this->option;
        }

        return $this->option[$name] ?? '';
    }

    public function table(string $table): static
    {
        $this->option['table'] = $table;
        return $this;
    }

    public function name(string $table): static
    {
        $this->option['table'] = $this->prefix . $table;
        return $this;
    }

    public function limit($start, $end = null)
    {
        $this->option['limit'] = [$start, $end];
        return $this;
    }

    public function select()
    {
        $param = func_get_args();
        if (isset($this->option['select'])) {
            $this->option['select'] = array_merge($this->option['select'], $param);
        } else {
            $this->option['select'] =  $param;
        }
        return $this;
    }

    /**
     * @name: 获得一条数据
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function first($id = null)
    {
        if (!is_null($id)) {
            $this->where($this->accident->getPk(), $id);
        }

        return $this->connect->get($this, true);
    }

    public function get()
    {
        return $this->connect->get($this);
    }

    public function __get($name)
    {
        return $this->option[$name] ?? null;
    }
}
