<?php

namespace Anng\event;

use Anng\lib\facade\Table;

class Close
{
    public function run(\Swoole\Server $server, $fd, $reactorId)
    {
        Table::name('fd')->del($server->getWorkerId() . $fd . $reactorId);
    }
}
