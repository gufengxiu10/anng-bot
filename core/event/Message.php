<?php

declare(strict_types=1);

namespace Anng\event;

use Anng\lib\facade\Reflection;
use \Swoole\WebSocket\Server;
use \Swoole\WebSocket\Frame;

class Message
{
    public function run(Server $server, Frame $frame)
    {
        dump(get_class_methods($frame));
        dump(get_class_methods($server));
        Reflection::setDefaultMethod('run', ['server' => $server, 'frame' => $frame])->instance('\\app\\event\\Message');
    }
}
