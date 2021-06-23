<?php
/*
 * @Description: 请求级容器
 * @Author: ANNG
 * @Date: 2021-05-20 16:54:06
 * @LastEditTime: 2021-06-23 10:37:57
 * @LastEditors: ANNG
 */

declare(strict_types=1);

namespace Anng\lib\container;

use Swoole\Coroutine;

class Request
{
    protected array $instances = [];
    protected array $alias = [];

    public function bind($alias, $name, $value = '')
    {
        $this->alias[$this->getTopCid()][$alias] = $name;
        if (!empty($value)) {
            $this->set($name, $value);
        }

        return $this;
    }

    public function get($name)
    {
        return $this->instances[$this->getTopCid()][$name];
    }

    public function set($name, $value): static
    {
        dump($value::class);
        $this->instances[$this->getTopCid()][$name] = $value;
        return $this;
    }

    public function clear(): void
    {
        if (!$this->info()) {
            return;
        }

        unset($this->instances[$this->getTopCid()]);
        unset($this->alias[$this->getTopCid()]);
    }

    protected function info()
    {
        return $this->instances[$this->getTopCid()] ?? false;
    }


    public function has($name)
    {
        if ($this->info()) {
            return isset($this->info()[$name]) ? true : false;
        }
        return false;
    }

    protected function getTopCid()
    {
        $id = Coroutine::getPcid();
        if ($id < 0) {
            return Coroutine::getCid();
        }

        return $this->getTopCid();
    }
}
