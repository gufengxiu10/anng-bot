<?php

declare(strict_types=1);

namespace Anng\lib\app;

use Anng\lib\facade\Annotations;
use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Reflection;
use Swoole\Http\Server as HttpServer;
use Swoole\WebSocket\Server as WebSocketServer;

use Anng\event\{
    Close,
    Connect,
    Open,
    Task,
    Websocket,
    websocket\Message as websocketMessage,
    websocket\Request as websocketRequest,
    WorkerStart
};

use Anng\lib\reflection\ReflectionClass;

class Server
{
    public $server;

    public function run()
    {
        switch (Config::get('server.server')) {
            case 'http':
                $this->server = new HttpServer(Config::get('server.host'), Config::get('server.prot'));
                break;
            case 'websocket':
                $this->server = new WebSocketServer(Config::get('server.host'), Config::get('server.prot'));
                break;
        }

        $this->server->set([
            'worker_num'                => Config::get('app.work_num'),
            'task_enable_coroutine'     => true,
            'task_worker_num'           => Config::get('server.task_worker_num'),
            'enable_static_handler'     => true,
            'package_max_length'        => 20 * 1024 * 1024,
            'document_root'             => "/www/abot/storage",
            'static_handler_locations'  => ['/public', '/app/images'],
        ]);

        //进程启动
        $this->server->on('WorkerStart', $this->ico(WorkerStart::class));
        //有新连接进入
        $this->server->on('Connect', $this->ico(Connect::class));
        //握手成功后调用
        $this->server->on('open', $this->ico(Open::class));

        switch (Config::get('app.server')) {
            case 'websocket':
                $this->server->on('message', $this->ico(websocketMessage::class));
                $this->server->on('request', $this->ico(websocketRequest::class));
                break;
            default:
                $this->server->on('request', $this->ico(Websocket::class, 'request'));
        }

        $this->server->on('task', $this->ico(Task::class));
        $this->server->on('finish', $this->ico(Task::class, 'finish'));

        //断开连接
        $this->server->on('close', $this->ico(Close::class));
        $this->server->start();
    }

    protected function ico($className, $func = ''): array
    {
        if (empty($func)) {
            $func = 'run';
        }
        return [(new ReflectionClass($className))->instance(), $func];
    }

    /**
     * @name: 启动任务调度器
     * @author: ANNG
     * @Date: 2021-01-27 15:39:46
     * @return {*}
     */
    public function crontabStart(): void
    {
        Crontab::setTask(Config::get('crontab'))
            ->run();
    }

    /**
     * @name: 创建Mysql连接池
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-01-27 15:41:03
     * @return {*}
     */
    public function createMysqlPool()
    {
        Db::setConfig(Config::get('datebase'))
            ->create();
    }

    public function loadAnnotation()
    {
        Annotations::load()->run();
    }
}
