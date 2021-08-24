<?php

declare(strict_types=1);

namespace Anng\utils;

class ApplicationContext
{
    private static $conation;

    public static function setConation($conation)
    {
        self::$conation = $conation;
    }

    public static function get($name)
    {
        return self::$conation->get($name);
    }
}
