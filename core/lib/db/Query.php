<?php

declare(strict_types=1);

namespace Anng\lib\db;

use Anng\lib\contract\AppInterface;
use Anng\lib\contract\db\PoolInterface;

class Query
{
    private $connect;

    public function __construct(private AppInterface $app)
    {
        $pool = $this->app->get(PoolInterface::class);
        $this->connect = new Connect($pool);
    }

    public function name()
    {
        return $this->connect->send('SELECT * FROM pixiv_article');
    }

    public function table()
    {
        # code...
    }
}
