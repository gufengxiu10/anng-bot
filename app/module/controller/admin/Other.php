<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\App;
use Anng\plug\oss\aliyun\Client;
use Anng\plug\oss\Oss;
use app\BaseController;


class Other extends BaseController
{
    public function import()
    {
        $oss = new Oss(new Client, 'LTAI5tLvqywRNs6HDHoR4kx1', 'HeDGGhn4Dx8lMJ0KyNkvHwc7An5lYG');

        $ki = $oss->setBucket('cic-pixiv')->upload(App::rootPath('public/images'));
        return 1;
        // App::getServer()->task([
        //     'name'      => GoodsImport::class,
        // ]);
        // return '完成';
        // $ik = App::getServer()->task(10);
        // dump($ik);
        // return;
        // 'brand_id', [165, 3519]
    }
}
