<?php

declare(strict_types=1);

namespace Anng\event;

use Anng\lib\facade\Reflection;
use app\cq\App;
use \Swoole\WebSocket\Server;
use \Swoole\WebSocket\Frame;

class Message
{
    public function run(Server $server, Frame $frame)
    {
        if (class_exists(App::class)) {
            Reflection::setDefaultMethod('run', ['server' => $server, 'frame' => $frame])->instance(App::class);
        } else {
            Reflection::setDefaultMethod('run', ['server' => $server, 'frame' => $frame])->instance('\\app\\event\\Message');
        }
    }
}
