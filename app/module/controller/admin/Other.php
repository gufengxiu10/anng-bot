<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\App;
use Anng\lib\facade\Db;
use app\BaseController;
use app\task\GoodsImport;
use Swoole\Coroutine\System;
use Swoole\Coroutine\WaitGroup;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Validator\Constraints\Type;

use function Co\run;

class Other extends BaseController
{
    public function import()
    {
        App::getServer()->task([
            'name'      => GoodsImport::class,
        ]);
        return '完成';
        // $ik = App::getServer()->task(10);
        // dump($ik);
        // return;
        // 'brand_id', [165, 3519]

    }
}
