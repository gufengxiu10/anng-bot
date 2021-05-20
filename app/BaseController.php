<?php

namespace app;


class BaseController
{
    protected  function service(string $class = '')
    {
        if (empty($class)) {
        }
        return new $class;
    }
}
