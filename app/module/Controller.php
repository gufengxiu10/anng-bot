<?php

namespace app\module;

class Controller
{
    protected  function service($class)
    {
        return new $class;
    }
}
