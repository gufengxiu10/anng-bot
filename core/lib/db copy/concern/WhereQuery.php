<?php

declare(strict_types=1);


namespace Anng\lib\db\concern;


trait WhereQuery
{
    /**
     * @name: 条件
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:10:22
     * @return {*}
     */
    public function where(string|callable $field, $condition = null, $value = null): static
    {
        if (is_callable($field)) {
            $query = (new static($this->connect));
            $field($query);
            $this->option['where']['AND'][] = $query;
        } else {
            $this->option['where']['AND'][] = [$field, $condition, $value];
        }

        return $this;
    }

    /**
     * @name: 条件
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-02-02 16:10:22
     * @return {*}
     */
    public function whereOr(string|callable $field, $condition = null, $value = null)
    {
        if (is_callable($field)) {
            $query = (new static($this->connect));
            $field($query);
            $this->option['where']['OR'][] = $query;
        } else {
            $this->option['where']['OR'][] = [$field, $condition, $value];
        }

        return $this;
    }
}
