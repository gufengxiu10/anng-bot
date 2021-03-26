<?php

declare(strict_types=1);

namespace Anng\lib\app;

use Anng\lib\facade\Annotations;
use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Env;
use Anng\lib\facade\Reflection;
use Swoole\Http\Server as HttpServer;
use Swoole\WebSocket\Server as WebSocketServer;

class Server
{
    public function run()
    {
        switch (Config::get('app.server')) {
            case 'http':
                $this->server = new HttpServer(Config::get('app.ip'), Config::get('app.prot'));
                break;
            case 'websocket':
                $this->server = new WebSocketServer(Config::get('app.ip'), Config::get('app.prot'));
                break;
        }

        $this->server->set([
            'worker_num' => Config::get('app.work_num'),
            // 'hook_flags' => SWOOLE_HOOK_ALL
        ]);

        //进程启动
        $this->server->on('WorkerStart', [$this->ico('WorkerStart'), 'run']);
        //有新连接进入
        $this->server->on('Connect', [$this->ico('Connect'), 'run']);

        $this->server->on('request', [$this->ico('request'), 'run']);

        //握手成功后调用
        $this->server->on('open', [$this->ico('Open'), 'run']);

        if (Config::get('app.server') == 'websocket') {
            //接收客户端数据时触发
            $this->server->on('message', [$this->ico('Message'), 'run']);
        }

        //断开连接
        $this->server->on('close', [$this->ico('Close'), 'run']);
        $this->server->start();
    }

    public function ico($method, $argc = [])
    {
        $className = "\\Anng\\event\\" . ucfirst($method);
        return Reflection::instance($className, $argc);
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
