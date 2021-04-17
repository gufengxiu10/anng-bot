<?php

namespace app\module;

class Controller
{
    protected  function service(string $class = '')
    {
        if (empty($class)) {
        }
        return new $class;
    }
}
