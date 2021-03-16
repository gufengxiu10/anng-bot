<?php

namespace Anng\event;

use Anng\lib\facade\Annotations;
use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Swoole\Http\Server;

class WorkerStart
{

    public function run(Server $server)
    {
        //创建连接池
        Db::setConfig(Config::get('datebase'))->create();

        //加载controller的全部注解
        Annotations::load()->run();

        //启动任务调度
        if ($server->getWorkerId() == 0) {
            Crontab::setTask(Config::get('crontab'))->run();
        }
    }
}
