<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\Db as Db;
use app\BaseController;

class Other extends BaseController
{
    public function import()
    {
        dump(Db::table('pixiv_article')->where('title', 10)->get());
    }
}
