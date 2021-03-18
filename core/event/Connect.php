<?php

namespace Anng\event;

use Anng\lib\facade\Table;

class Connect
{
    public function run(\Swoole\Server $server, $fd, $reactorId)
    {
        Table::name('fd')->set($server->getWorkerId() . $fd . $reactorId, [
            'fd' => $fd,
            'workerId' => $server->getWorkerId()
        ]);

        dump('连接成功');
    }
}
