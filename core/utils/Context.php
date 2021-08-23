<?php

declare(strict_types=1);

namespace Anng\utils;

use Swoole\Coroutine;

final class Context
{
    protected static $pool = [];

    public static function get(string $name = '')
    {
        if ($name == '') {
            return self::$pool;
        }
        return self::$pool[self::getTopCid()][$name] ?? '';
    }

    public static function set($key, $value): void
    {
        self::$pool[self::getTopCid()][$key] = $value;
    }

    protected static function getTopCid()
    {
        $id = Coroutine::getPcid();
        if ($id < 0) {
            return Coroutine::getCid();
        }

        return self::getTopCid();
    }

    public static function has($name)
    {
        $pool = static::$pool[self::getTopCid()];

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

    public static function delete()
    {
        unset(self::$pool[self::getTopCid()]);
    }
}
