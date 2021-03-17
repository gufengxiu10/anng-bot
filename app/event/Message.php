<?php

namespace app\event;

use Anng\lib\facade\Messages;

class Message
{
    public function run($server, $frame)
    {
        if (Messages::exists($frame->data)) {
            Messages::search($frame->data);
        }
    }
}
