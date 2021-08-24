<?php

declare(strict_types=1);

namespace Anng\lib\app;

use Anng\lib\App;
use Anng\lib\facade\Config;
use Anng\lib\facade\Container;
use Anng\lib\facade\Env;
use Anng\lib\facade\Route;

class Bootstrap
{
    public function __construct(private App $app)
    {
    }

    public function start()
    {
        $this->configLoad();
        $this->envLoad();
        // $this->providerRegister();
        $this->routeLoad();
        $this->providerLoad();
        $this->register();
    }

    /**
     * @name: 容器文件加载
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function providerLoad()
    {
        $path = $this->app->appPath() . 'Provider.php';
        if (file_exists($path)) {
            $provider =  include_once $path;
            foreach ($provider as $key => $val) {
                if (class_exists($val)) {
                    $this->app->instance($key, new $val());
                }
            }
        }

        return $this;
    }

    /**
     * @name: 加载ENV配置文件
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function envLoad()
    {
        Env::setPath($this->app->getEnv())->loading();
    }

    /**
     * @name: 配置文件加载
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function configLoad()
    {
        //加载配置文件
        Config::load();
    }

    /**
     * @name: 路由加载
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function routeLoad()
    {
        //路由加载
        Route::load();
    }

    /**
     * 注册相关类到全局上下文
     */
    private  function register()
    {
        
    }
}
