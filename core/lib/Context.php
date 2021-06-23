<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Coroutine;

class Context
{
    protected $pool = [];

    public function get(string $name)
    {
        return $this->pool[$this->getTopCid()][$name] ?? '';
    }

    public  function push($key, $value): void
    {
        $this->pool[$this->getTopCid()][$key] = $value;
    }

    protected function getTopCid()
    {
        $id = Coroutine::getPcid();
        if ($id < 0) {
            return Coroutine::getCid();
        }

        return $this->getTopCid();
    }

    public function has($name)
    {
        $pool = $this->pool[$this->getTopCid()];

        if (class_exists($name) || interface_exists($name)) {
            foreach ($pool as $item) {
                if ($item instanceof $name) {
                    return $item;
                }
            }
        }

        if (isset($pool[$name])) {
            return $pool[$name];
        }

        return false;
    }

    public function delete()
    {
        unset($this->pool[$this->getTopCid()]);
    }
}
