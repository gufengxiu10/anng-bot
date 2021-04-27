<?php

declare(strict_types=1);

namespace Anng\lib\db\biluder\sql;


trait Conditions
{
    /**
     * @name: 设置表
     * @param {*}
     * @author: ANNG
     * @Date: 2021-01-28 10:41:08
     * @return static
     */
    public function name($val): static
    {
        $this->parse->table = is_null($this->config->get('prefix')) ? $val :  $this->config->get('prefix') . $val;
        return $this;
    }

    /**
     * @name: 设置查询字段
     * @param string|array|bool $val 字段值
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 13:47:17
     * @return {*}
     */
    public function field(string|array|bool $val): static
    {
        if ($val === false) {
            $val = '*';
        }
        $this->parse->field = $val;
        return $this;
    }

    /**
     * @name: 别名
     * @param string $val 别名值
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-28 13:51:43
     * @return {*}
     */
    public function alias(string $val): static
    {
        $this->parse->alias = $val;
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
    public function where(string|callable $field, $condition = null, $value = null)
    {
        if (is_callable($field)) {
            $field($this);
            return $this;
        }

        if (empty($field)) {
            return $this;
        }

        $where = [$field, $condition, $value];
        array_push($this->parse->where, $where);
        return $this;
    }

    /**
     * @name: limit条件
     * @param {*} $start
     * @param {*} $end
     * @author: ANNG
     * @return {*}
     */
    public function limit($start, $end = null): static
    {
        $limit = $start;
        if (!is_null($end)) {
            $limit .= ',' . $end;
        }

        $this->parse->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
}
