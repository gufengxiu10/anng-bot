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

    /**
     * @name: 获得一条数据
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function first()
    {
        $this->connect->get($this, true);
    }
}
