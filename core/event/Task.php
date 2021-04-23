<?php

namespace Anng\event;

use Anng\lib\facade\Table;
use Anng\lib\reflection\ReflectionClass;
use Anng\lib\task\Coroutine;
use Swoole\Server;
use Swoole\Server\Task as ServerTask;
use Swoole\Timer;

class Task
{
    public function run(Server $server, ServerTask $task)
    {
        $nTask = new Coroutine($task);
        if ($nTask->check()) {
            $retrunData = (new ReflectionClass($nTask->getController()))->sendMethod($nTask->getAction(), [$nTask->getParam()]) ?: [];
            if (isset($task->data['finish']) && $task->data['finish'] === true) {
                // $task->finish($retrunData);
            }
        }
    }


    public function finish($serv, $task_id, $data)
    {
        echo "AsyncTask[{$task_id}] Finish: {$data}" . PHP_EOL;
    }
}
