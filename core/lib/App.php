<?php

namespace Anng\lib;

use Anng\lib\app\Bootstrap;
use Anng\lib\app\Server;
use ReflectionException;

class App extends Container
{
    //根目录
    protected $rootPath;

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Shanghai");
        $this->rootPath = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR;
        $this->server = new Server;
    }

    public function getServer()
    {
        return $this->server->server;
    }

    public function start()
    {
        (new Bootstrap($this))->start();
        try {
            $this->server->run();
        } catch (ReflectionException $th) {
            dump('反射失败：' . $th->getMessage() . '|' . $th->getLine() . '|' . $th->getFile());
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

    public function rootPath(string $value = ''): string
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
    public function appPath()
    {
        return $this->rootPath('app' . DIRECTORY_SEPARATOR);
    }
}
