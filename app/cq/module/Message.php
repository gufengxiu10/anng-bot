<?php

declare(strict_types=1);

namespace app\cq\module;

use app\cq\Cq;

class Message
{
    public function sendPrivate(string $userId, string $groudId, string|Cq $message): string
    {
        return $this->set('send_private_msg', [
            'user_id' => $userId,
            'group_id' => $groudId,
            'message' => $message
        ]);
    }

    public function sendGroup(string $groudId, string|Cq $message): string
    {
        return $this->set('send_private_msg', [
            'group_id' => $groudId,
            'message' => $message
        ]);
    }

    public function set(string $uri, $params): string
    {
        return json_encode([
            'action' => $uri,
            'params' => $params
        ]);
    }
}
