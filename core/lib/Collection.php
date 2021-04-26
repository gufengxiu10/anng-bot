<?php

declare(strict_types=1);

namespace Anng\lib;

use ArrayAccess;

class Collection implements ArrayAccess
{
    protected array $items = [];

    public function __construct($data)
    {
        $this->items = $this->convertToArray($data);
    }

    public static function make($data)
    {
        return new static($data);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function each(callable $done)
    {
        foreach ($this->items as $key => &$item) {
            $oitem = static::make($item);
            $done($oitem, $key);
            $item = $oitem->toArray();
        }

        return $this;
    }

    /**
     * @name: 判断字段是否存在 
     * @param {*} $name
     * @author: ANNG
     * @return {*}
     */
    public function has($name)
    {
        return  isset($this->items[$name]) ?? false;;
    }


    public function push(string $name, $value, $empty = false): static
    {

        if ($empty === true && empty($value)) {
            return $this;
        }

        if (!$this->has($name)) {
            $this->items[$name] = [];
        }

        array_push($this->items[$name], $value);
        return $this;
    }

    public function add(string $name, $value, $empty = false)
    {
        if ($empty === true && empty($value)) {
            return $this;
        }

        $this->items[$name] = $value;
        return $this;
    }


    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetSet($offset, $val)
    {
        if (is_null($offset)) {
            $this->items[] = $val;
        } else {
            $this->items[$offset] = $val;
        }
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }



    public function __get($name)
    {
        return $this->items[$name];
    }

    public function __set($name, $value)
    {
        $this->items[$name] = $value;
    }

    /**
     * 转换成数组
     *
     * @access public
     * @param mixed $itemss 数据
     * @return array
     */
    protected function convertToArray($itemss): array
    {
        // if ($itemss instanceof self) {
        //     return $itemss->all();
        // }

        return (array) $itemss;
    }
}
