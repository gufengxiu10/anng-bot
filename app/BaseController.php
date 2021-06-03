<?php

namespace app;

use app\traits\Api;

class BaseController
{
    use Api;

    protected $service;

    protected function service(string $class = '')
    {
        if (empty($class)) {
            $class = $this->service;
        }

        return new $class;
    }
}
