<?php

namespace Anng\lib;

use Anng\lib\app\Server;
use Anng\lib\facade\Config;
use Anng\lib\facade\Env;
use Anng\lib\facade\Route;
use Anng\lib\facade\Table as FacadeTable;
use ReflectionException;

use Swoole\Table;


class App
{
    //根目录
    protected $rootPath;

    public function __construct()
    {
        date_default_timezone_set("Asia/Shanghai");
        $this->rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
    }

    public function init()
    {
        //加载ENV文件
        Env::setPath($this->getEnv())->loading();

        //加载配置文件
        Config::load();
        //路由加载
        Route::load();

        //创建fd共享内存
        FacadeTable::create('fd', [
            ['fd', Table::TYPE_INT, 64],
            ['workerId', Table::TYPE_INT, 64],
            ['isBot', Table::TYPE_INT, 2]
        ]);
    }

    public function start()
    {
        $this->init();
        try {
            (new Server)->run();
        } catch (ReflectionException $th) {
            dump('反射失败：' . $th->getMessage());
        }
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

    /**
     * @name: 路由目录
     * @author: ANNG
     * @Date: 2021-01-11 09:38:21
     * @return string
     */
    public function getRoutePath(): string
    {
        return $this->rootPath . 'route' . DIRECTORY_SEPARATOR;
    }

    /**
     * @name: 应用目录
     * @param {*}
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-24 14:03:53
     * @return {*}
     */
    public function getAppPath()
    {
        return $this->rootPath . 'app' . DIRECTORY_SEPARATOR;
    }
}
