<?php

namespace Anng\event;

use Anng\lib\facade\Annotations;
use Anng\lib\facade\Config;
use Anng\lib\facade\Crontab;
use Anng\lib\facade\Db;
use Anng\lib\facade\Table;
use Swoole\Http\Server;
use Swoole\Timer;

class Work
{

    /**
     * @name: 进程启动
     * @param {Server} $server
     * @author: ANNG
     * @return {*}
     */
    public function start(Server $server)
    {
        //创建连接池
        Db::setConfig(Config::get('datebase'))->create();

        //加载controller的全部注解
        Annotations::load()->run();

        //启动任务调度
        if ($server->getWorkerId() == 0) {
            Crontab::setTask(Config::get('crontab'))->run($server);
            Timer::tick(1000, function () {
                // dump(Table::name('fd')->count());
            });
        }
    }

    /**
     * @name: 进程结束
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function stop()
    {
        # code...
    }

    /**
     * @name: 进程退出
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function exit()
    {
        # code...
    }

    /**
     * @name: 进程异常
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function error()
    {
        # code...
    }
}
