<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\reflection\ReflectionClass;

abstract class Service
{
    protected $loads = [];

    public function __get(string $name): mixed
    {
        if (isset($this->loads[$name])) {
            return new ReflectionClass($this->loads[$name]);
        }

        return $this->$name;
    }
}
