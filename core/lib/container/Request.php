<?php
/*
 * @Description: 请求级容器
 * @Author: ANNG
 * @Date: 2021-05-20 16:54:06
 * @LastEditTime: 2021-05-20 17:26:31
 * @LastEditors: ANNG
 */

declare(strict_types=1);

namespace Anng\lib\container;

use Anng\lib\Container;
use Swoole\Coroutine;

class Request
{
    protected array $instances = [];

    public function get($name)
    {
        return $this->instances[$this->getTopCid()][$name];
    }

    public function set($name, $value): static
    {
        $this->instances[$this->getTopCid()][$name] = $value;
        return $this;
    }

    public function clear(): void
    {
        if (!$this->info()) {
            return;
        }

        unset($this->instances[$this->getTopCid()]);
    }

    protected function info()
    {
        return $this->instances[$this->getTopCid()] ?? false;
    }


    protected function has($name)
    {
        return $this->info() ?? ($this->info()[$name] ? true : false);
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
