<?php

use Anng\lib\App;
use Anng\lib\Container;
use Anng\lib\Crontab;
use Anng\lib\Env;
use Anng\lib\Config;
use Anng\lib\Connect;
use Anng\lib\Facade;
use Anng\lib\Redis;
use Anng\lib\Db;
use Anng\lib\Table;
use Anng\lib\Annotations;
use Anng\lib\cache\Cache;
use Anng\lib\Exception;
use Anng\lib\Messages;
use Anng\lib\Route;
use Anng\lib\Request;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$container = new App;

$container->bind([
    'App'               => App::class,
    'Config'            => Config::class,
    'Redis'             => Redis::class,
    'Env'               => Env::class,
    'Db'                => Db::class,
    'Crontab'           => Crontab::class,
    'Connect'           => Connect::class,
    'Table'             => Table::class,
    'Annotations'       => Annotations::class,
    'Messages'          => Messages::class,
    'Request'           => Request::class,
    'Response'          => Response::class,
    'Messages'          => Messages::class,
    'Route'             => Route::class,
    'Cache'             => Cache::class,
    'Exception'         => Exception::class
]);

require_once 'Helper.php';

return $container;
