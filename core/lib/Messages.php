<?php

declare(strict_types=1);

namespace Anng\lib;

class Messages
{
    private array $func = [];
    private array $aliases = [];
    private array $keys = [];

    public function set($key, $func,  $aliases = [])
    {
        $this->func[$key][] = $func;
        $this->aliases[$key] = $aliases;
        $this->keys[] = $key;
        $this->keys = array_merge($this->keys, $this->aliases[$key]);
    }

    /**
     * @name: 
     * @param {*} $key
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-17 14:32:47
     * @return {*}
     */
    public function exists($key): bool
    {
        return in_array($key, $this->keys);
    }

    public function search($key)
    {
        $key = trim($key);
        if (!in_array($key, $this->keys)) {
            return;
        }

        foreach ($this->aliases as $k => $v) {
            if (in_array($key, $v)) {
                $key = $k;
                break;
            }
        }

        $func = $this->func[$key];
        foreach ($func as $value) {
            $reftion = $value->getDeclaringClass();
            $object = $reftion->newInstance();
            $method = $value->getName();
            $object->$method();
        }
    }
}
