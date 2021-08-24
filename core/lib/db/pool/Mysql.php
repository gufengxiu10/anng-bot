<?php

declare(strict_types=1);

namespace Anng\lib\db\pool;

use Anng\lib\db\Pool;
use PDO;
use Swoole\Database\PDOConfig;
use Swoole\Database\PDOPool;

class Mysql
{
    public function create()
    {
        $config = (new PDOConfig)->withHost('bj-cdb-ozdvjhny.sql.tencentcdb.com')
            ->withPort(60977)
            ->withDbname('pixiv')
            ->withCharset('utf8mb4')
            ->withUsername('gufengxiu10')
            ->withPassword('Freedomx102')
            ->withOptions([
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        return new PDOPool($config);
    }
}
