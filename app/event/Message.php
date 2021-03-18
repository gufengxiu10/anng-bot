<?php

namespace app\event;

use Anng\lib\facade\Messages;
use \Swoole\WebSocket\Server;

class Message
{
    public function run(Server $server, $frame)
    {
        // Messages::search();
        dump($frame);
    }
}
