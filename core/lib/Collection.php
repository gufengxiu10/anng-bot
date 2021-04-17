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
