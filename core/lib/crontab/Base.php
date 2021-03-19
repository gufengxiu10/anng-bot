<?php

declare(strict_types=1);

namespace Anng\lib\crontab;

abstract class Base
{
    protected $server;

    public function _make($server)
    {
        $this->server = $server;
    }
}
