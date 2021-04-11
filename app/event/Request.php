<?php

namespace app\event;

use Anng\lib\facade\Messages;
use \Swoole\WebSocket\Server;

class Request
{
    public function run(Server $server, $frame)
    {
        // Messages::search();
        dump($frame);
    }
}
