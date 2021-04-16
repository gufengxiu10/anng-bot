<?php

declare(strict_types=1);


namespace Anng\lib;

use Anng\lib\facade\Reflection;

class Exception
{
    private $ignoreReport = [];

    public function load($class)
    {
        $object = Reflection::instance($class);
    }
}
