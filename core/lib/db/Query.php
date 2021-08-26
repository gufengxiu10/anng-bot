<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\contract\AppInterface;
use Anng\lib\contract\db\PoolInterface;
use Anng\lib\contract\db\QueryInterface;
use Illuminate\Database\Connection;

class Query implements QueryInterface
{
    private $connect;

    //表名
    private string $table;

    private string $alias;

    //获得指定列
    private array $field;

    //条件
    private array $where;

    //条数
    private array $limit;

    public function __construct(private AppInterface $app)
    {
        $pool = $this->app->get(PoolInterface::class);
        $this->connect = new Connect($pool);
        $this->parse = new Parse($this);
    }

    public function name()
    {
        return $this->connect->getAll('SELECT * FROM pixiv_article');
    }

    /**
     * 设置表名
     */
    public function table(string $name)
    {
        $this->table = $name;
        return $this;
    }

    /**
     * 设置表别名
     */
    public function alias(string $alias): static
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * 条件
     */
    public function where(string|callable|array $name, mixed $condition = null, $value = null): static
    {
        if (is_array($name)) {
            $where = [];
            foreach ($name as $value) {
                $where[] = [$value[0], $value[1], $value[2] ?? null, $value[3] === 'OR' ? 'OR' : 'AND'];
            }
            $this->where[] = $where;
        } else {
            $this->where[] = [$name, $condition, $value ?? null, 'AND'];
        }

        return $this;
    }

    public function limit($start, $end = null): static
    {
        $this->limit = [$start, $end];
        return $this;
    }

    public function select(array $column): static
    {
        $this->field = $column;
        return $this;
    }

    public function get(): array
    {
        dump($this->parse->select());
        return [];
    }

    public function getOption($name)
    {
        return $this->$name;
    }
}
