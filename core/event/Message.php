<?php

declare(strict_types=1);

namespace Anng\event;

use \Swoole\WebSocket\Server;
use \Swoole\WebSocket\Frame;

class Message
{
    public function run(Server $server, Frame $frame)
    {
    }
}
