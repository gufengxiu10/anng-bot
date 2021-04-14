<?php

namespace app\module\service\article;

use Anng\lib\facade\Db;

class Index
{
    public function lists($page, $limit)
    {
        return Db::name('article')
            ->limit($page, $limit)
            ->select();
    }

    public function info($id)
    {
        # code...
    }

    public function insert($data)
    {
        Db::name('article')->insert($data);
    }
}
