<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\App;
use Anng\lib\facade\Env;
use Anng\plug\oss\aliyun\Client;
use Anng\plug\oss\Oss;
use app\BaseController;


class Other extends BaseController
{
    public function import()
    {
        $oss = new Oss(new Client, Env::get('AK'), Env::get('AS'));
        $oss->setBucket(Env::get('BUCKET'))->upload(App::rootPath('public/images'));
        return 1;
        // App::getServer()->task([
        //     'name'      => GoodsImport::class,
        // ]);
        // return '完成';
        // $ik = App::getServer()->task(10);
        // return;
        // 'brand_id', [165, 3519]
    }
}
