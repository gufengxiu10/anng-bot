<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\contract\RequestInterface;
use Anng\lib\facade\App;
use app\BaseController;
use app\task\Goods;
use Swlib\Saber;
use Swlib\SaberGM;

class Other extends BaseController
{
    private $header = [
        'Accept'            => 'application/json, text/javascript, */*; q=0.01',
        'siteCode'          => 'sdszfcg',
        'Content-Type'      => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Origin'            => 'http://ggzyjyzx.shandong.gov.cn',
        'Referer'           => 'http://ggzyjyzx.shandong.gov.cn/wssc/sdszfcg/portal/login.html?name=2',
        // 'Referer'           => 'http://ggzyjyzx.shandong.gov.cn/wssc/sdszfcg/backend/',
        'X-Requested-With'  => 'XMLHttpRequest',
        'Cookie'            => 'SPEPLUSJSESSIONID=7c1e55f6-e7a2-41da-a3a4-092f6310a7b0',
        'Host'              => "ggzyjyzx.shandong.gov.cn",
        'User-Agent'        => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36 Edg/90.0.818.51'
    ];

    public function import(RequestInterface $request)
    {
        $file = $request->file();
        dump($file->move('public')->getRelativePath());
        // $file->move()
        // $oss = new Oss(new Client, Env::get('AK'), Env::get('AS'));
        // $oss->setBucket(Env::get('BUCKET'))->upload(App::rootPath('public/images'));
        // return 1;
        // App::getServer()->task([
        //     'name'      => GoodsImport::class,
        // ]);
        // return '完成';
        // $ik = App::getServer()->task(10);
        // return;
        // 'brand_id', [165, 3519]
    }
}
