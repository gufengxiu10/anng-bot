<?php

declare(strict_types=1);

namespace Anng\lib\task;

use Anng\lib\Collection;
use Swoole\Server\Task;

class Coroutine
{
    // 来自哪个Worker进程ID
    private $workerId;
    //任务的编号
    private $id;
    //任务的类型，taskwait, task, taskCo, taskWaitMulti 可能使用不同的 flags
    private $flags;
    //投递时间
    private $dispatchTime;

    //任务的数据
    private $data = [];

    private $param = [];

    private string $controller = '';

    private string $action = 'run';

    public function __construct(private Task $task)
    {
        $this->workerId = $this->task->worker_id;
        $this->id = $this->task->id;
        $this->flags = $this->task->flags;
        $this->dispatchTime = $this->task->dispatch_time;
        $this->data = Collection::make($this->task->data);
        $this->param = $this->data->param ?? [];
        if ($this->data->has('name')) {
            $this->controller = $this->data->name;
        }

        if ($this->data->has('action')) {
            $this->action = $this->data->action;
        }
    }


    public function check()
    {
        return class_exists($this->controller);
    }

    public function __get($name)
    {
        return $this->data->$name;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParam()
    {
        return $this->param ?: [];
    }
}
