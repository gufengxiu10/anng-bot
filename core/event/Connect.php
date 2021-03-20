<?php

namespace Anng\event;

use Anng\lib\facade\Table;
use Swoole\WebSocket\Server;

class Connect
{
    public function run(\Swoole\Server $server, $fd, $reactorId)
    {
        // dump('连接成功');
    }
}
