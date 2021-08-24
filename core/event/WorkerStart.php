<?php

namespace Anng\event;

use Anng\lib\contract\db\PoolInterface;
use Anng\lib\db\connect\Mysql;
use Anng\lib\facade\Annotations;
use Anng\lib\facade\App;
use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Table;
use Swoole\Http\Server;
use Swoole\Timer;

class WorkerStart
{

    public function run(Server $server)
    {
        //创建连接池
        App::get(PoolInterface::class)->create();

        //加载controller的全部注解
        Annotations::load()->run();
        //启动任务调度
        if ($server->getWorkerId() == 0) {
            Crontab::setTask(Config::get('crontab'))->run($server);
            Timer::tick(1000, function () {
            });
        }
    }
}
