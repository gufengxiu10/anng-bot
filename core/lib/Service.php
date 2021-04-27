<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\reflection\ReflectionClass;

abstract class Service
{
    protected $loads = [];
    protected $loadsInstance = [];

    public function __get(string $name): mixed
    {
        if (isset($this->loads[$name])) {
            if (!isset($this->loadsInstance[$name])) {
                $this->loadsInstance[$name] = (new ReflectionClass($this->loads[$name]))->instance();
            }
            return $this->loadsInstance[$name];
        }

        return $this->$name;
    }
}
