<?php

declare(strict_types=1);

namespace app\cq\module;

use app\cq\Cq;

class Message
{
    /**
     * @name: 发送私聊
     * @param {string} $userId
     * @param {string} $groudId
     * @param {Cq} $message
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-17 10:07:44
     * @return {*}
     */
    public function sendPrivate(string $userId, string $groudId, string|Cq $message): string
    {
        return $this->set('send_private_msg', [
            'user_id' => $userId,
            'group_id' => $groudId,
            'message' => $message
        ]);
    }

    /**
     * @name: 发送群聊
     * @param {string} $groudId
     * @param {Cq} $message
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-17 10:07:58
     * @return {*}
     */
    public function sendGroup(string $groudId, array|Cq $message): string
    {
        return $this->set('send_group_msg', [
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
