<?php

namespace Anng\lib;

use Anng\lib\facade\Annotations;
use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Env;
use Anng\lib\facade\Reflection;
use Anng\lib\facade\Table as FacadeTable;
use Swoole\Coroutine\Http\Server;
use Swoole\Coroutine\Server\Connection;
use Swoole\Coroutine\Socket;
use Swoole\Process\Pool;
use Swoole\Table;
use Swoole\Timer;
use Swoole\WebSocket\Server as WebSocketServer;

class App
{
    private $service;

    //容器对象
    private $container;

    //根目录
    protected $rootPath;


    protected $fd;

    public function __construct()
    {
        date_default_timezone_set("Asia/Shanghai");
        $this->rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
    }


    public function init()
    {
        $configPath = $this->getConfigPath();
        //加载配置文件
        $files = [];
        if (is_dir($configPath)) {
            $files = glob($configPath . '*.php');
        }

        foreach ($files as $file) {
            Config::load($file, pathinfo($file, PATHINFO_FILENAME));
        }

        //加载ENV文件
        Env::setPath($this->getEnv())->loading();

        //创建共享内存
        FacadeTable::create([
            ['address', Table::TYPE_STRING, 64],
            ['port', Table::TYPE_STRING, 64],
        ]);
    }

    public function start()
    {
        $this->init();
        $this->server();
        //为进程定义名字
        // swoole_set_process_name('anng');
        // $this->pm = new Manager();
        // 
        // $this->pm->addBatch(3, function (Pool $pool, int $workerId) {
        //     $this->server($pool, $workerId);
        // });
        // $this->pm->setIPCType(SWOOLE_IPC_UNIXSOCK);
        // $this->pm->start();
    }

    private function server()
    {
        \Swoole\Coroutine::set([
            'hook_flags' => SWOOLE_HOOK_CURL
        ]);

        $this->server = new WebSocketServer('0.0.0.0', 9502);
        $this->server->set([
            'worker_num' => 3
        ]);

        //进程启动
        $this->server->on('WorkerStart', [$this->ico('WorkerStart'), 'run']);
        //有新连接进入
        $this->server->on('Connect', [$this->ico('Connect'), 'run']);
        //握手成功后调用
        $this->server->on('open', [$this->ico('Open'), 'run']);
        //接收客户端数据时触发
        $this->server->on('message', [$this->ico('Message'), 'run']);

        $this->server->on('close', function ($ser, $fd) {
            echo "client {$fd} closed\n";
        });

        $this->server->start();
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


    public function ico($method, $argc = [])
    {
        $className = "\\Anng\\event\\" . $method;
        return Reflection::instance($className, $argc);
    }

    public function loadAnnotation()
    {
        Annotations::load()->run();
    }

    /**
     * @name: 配置目录
     * @author: ANNG
     * @Date: 2021-01-11 09:38:21
     * @return string
     */
    public function getConfigPath(): string
    {
        return $this->rootPath . 'config' . DIRECTORY_SEPARATOR;
    }

    /**
     * @name: Env位置
     * @author: ANNG
     * @Date: 2021-01-11 09:41:40
     * @return string
     */
    public function getEnv()
    {
        return $this->rootPath;
    }

    public function getRootPath(string $value = ''): string
    {
        if (!empty($value)) {
            return $this->rootPath . $value;
        }

        return $this->rootPath;
    }
}
