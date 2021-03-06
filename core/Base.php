<?php

use Anng\lib\App;
use Anng\lib\Container;
use Anng\lib\Crontab;
use Anng\lib\Env;
use Anng\lib\Config;
use Anng\lib\Connect;
use Anng\lib\Redis;
use Anng\lib\Db;
use Anng\lib\Table;
use Anng\lib\Annotations;
use Anng\lib\cache\Cache;
use Anng\lib\Context;
use Anng\lib\contract\AppInterface;
use Anng\lib\contract\ContextInterface;
use Anng\lib\contract\db\PoolInterface;
use Anng\lib\contract\RequestInterface;
use Anng\lib\db\Pool;
use Anng\lib\Exception;
use Anng\lib\Messages;
use Anng\lib\Route;
use Anng\utils\ApplicationContext;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$container = new App;

$container->bind([
    'Config'            => Config::class,
    'Redis'             => Redis::class,
    'Env'               => Env::class,
    'Db'                => Db::class,
    'Crontab'           => Crontab::class,
    'Connect'           => Connect::class,
    'Table'             => Table::class,
    'Annotations'       => Annotations::class,
    'Messages'          => Messages::class,
    'Messages'          => Messages::class,
    'Route'             => Route::class,
    'Cache'             => Cache::class,
    'Exception'         => Exception::class,
    ContextInterface::class => Context::class,
    AppInterface::class     => App::class,
    PoolInterface::class    => Pool::class,
    RequestInterface::class  => fn (Container $container) => ($container->getInstance()->make('context'))->get('request')
]);
ApplicationContext::setConation($container);

require_once 'Helper.php';

$container->start();
