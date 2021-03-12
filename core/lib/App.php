<?php

namespace Anng\lib;

use Anng\lib\facade\Annotations;
use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Env;
use Anng\lib\facade\Messages;
use Anng\lib\facade\Table as FacadeTable;

use Co\Http\Server;
use ReflectionAttribute;
use ReflectionClass;
use Swoole\Process\Pool;
use Swoole\Table;
use Swoole\Timer;
use Symfony\Component\Finder\Finder;

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
            ['key', Table::TYPE_STRING, 64],
            ['data', Table::TYPE_STRING, 64],
        ]);
    }

    public function start()
    {
        //为进程定义名字
        // swoole_set_process_name('anng');
        $pm = new Manager();
        $this->init();
        $pm->addBatch(3, function (Pool $pool, int $workerId) {
            $this->server($pool, $workerId);
        });
        $pm->setIPCType(SWOOLE_IPC_UNIXSOCK);
        $pm->start();
    }

    private function server($pool, $workerId)
    {
        \Swoole\Coroutine::set([
            'hook_flags' => SWOOLE_HOOK_CURL
        ]);

        //启动任务调度
        if ($workerId == 0) {
            $this->crontabStart($pool, $workerId);
        }

        //创建连接池
        $this->createMysqlPool();

        //加载controller的全部注解
        $this->loadAnnotation();

        $this->server = new Server('0.0.0.0', 9502, false, true);
        $this->server->handle('/', function ($request, $ws) use ($pool, $workerId) {
            // dump(get_class_methods());
            if (!FacadeTable::exists('fd:' . $workerId . $request->fd)) {
                FacadeTable::set('fd:' .  $workerId . $request->fd, ['key' => $request->fd]);
            }

            dump(get_class_methods($pool));
            // dump(spl_object_id($ws) . '_' . $workerId . '_' . $request->fd);
            $ws->upgrade();
            while (true) {
                $frame = $ws->recv();
                if ($workerId ==  0) {
                    Timer::tick(1000, function () {
                        $table = FacadeTable::getinstance();
                        dump($table->count());
                    });
                }
            }
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


    public function ico($method, $argc)
    {
        $className = "\\Anng\\event\\" . $method;
        Reflection::setDefaultMethod('run', $argc)
            ->instance($className, $argc);
    }

    public function createTable()
    {
        $table = new Table(1024);
        $table->column('id', Table::TYPE_INT, 4);
        $table->column('fd', Table::TYPE_INT, 64);
        $table->create();
        return $table;
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

    public function getFd()
    {
        return $this->fd;
    }

    public function loadAnnotation()
    {
        Annotations::load()->run();
    }
}
