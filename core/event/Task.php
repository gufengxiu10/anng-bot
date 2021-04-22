<?php

namespace Anng\event;

use Anng\lib\facade\Table;
use Anng\lib\reflection\ReflectionClass;
use Swoole\Server;
use Swoole\Server\Task as ServerTask;
use Swoole\Timer;

class Task
{
    public function run(Server $server, ServerTask $task)
    {
        if (isset($task->data['name']) && class_exists($task->data['name'])) {
            if (!isset($task->data['action'])) {
                $task->data['action'] = 'run';
            }

            $retrunData = (new ReflectionClass($task->data['name']))->sendMethod($task->data['action'], $task->data['param'] ?? []) ?: [];
            if (isset($task->data['finish']) && $task->data['finish'] === true) {
                $task->finish($retrunData);
            }
        }
    }


    public function finish($serv, $task_id, $data)
    {
        echo "AsyncTask[{$task_id}] Finish: {$data}" . PHP_EOL;
    }
}
