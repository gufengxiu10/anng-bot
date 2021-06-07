<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\App;
use Anng\lib\facade\Env;
use Anng\plug\oss\aliyun\Client;
use Anng\plug\oss\Oss;
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

    public function import()
    {
        $this->login();
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


    public function login()
    {
        $s = microtime(true);
        $client = Saber::create([
            'headers' => $this->header,
            'retry_time' => 5
        ]);

        $urls = [];
        $res = SaberGM::get('http://ggzyjyzx.shandong.gov.cn/wssc/portalController/mallList?type=1', [
            'timeout' => 60,
            'retry_time' => 5,
            'headers' => $this->header
        ]);

        $listUrl = [];
        $body = json_decode((string)$res->getBody(), true);
        foreach ($body as $value) {
            foreach ($value['children'] as $v) {
                foreach ($v['children'] as $k) {
                    if (!empty($k['children'])) {
                        foreach ($k['children'] as $n) {
                            array_push($listUrl, [
                                'type' =>  $n['platformCatalogId'],
                                'num'  => 0
                            ]);
                        }
                    } else {
                        array_push($listUrl, [
                            'type' =>  $k['platformCatalogId'],
                            'num'  => 0
                        ]);
                    }
                }
            }
        }

        for ($i = 0; $i < 2; $i++) {
            go(function () use (&$listUrl) {
                while (true) {
                    if (empty($listUrl)) {
                        break;
                    }

                    $type = array_shift($listUrl);

                    try {
                        $url = "http://ggzyjyzx.shandong.gov.cn/wssc/mc_goods/" . $type['type'] . "?type=1&page=1&rows=16";
                        $request =  SaberGM::get($url, [
                            'timeout' => 60,
                            'retry_time' => 5,
                            'headers' => $this->header
                        ]);
                    } catch (\Throwable $th) {
                        if ($type['num'] > 3) {
                            dump(1 . "_" . $th->getMessage() . "_" . $type['type']);
                            file_put_contents('page.txt', json_encode([
                                'url' => $url
                            ], JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
                        } else {
                            $type['num']++;
                            array_push($listUrl, $type);
                        }
                        continue;
                    }

                    $body = json_decode((string)$request->getBody(), true);
                    $max = $body['page']['pages'];

                    $urls = [];
                    for ($i = 0; $i < $max; $i++) {
                        $page = $i + 1;
                        array_push($urls, [
                            'url'   => "http://ggzyjyzx.shandong.gov.cn/wssc/mc_goods/" . $type['type'] . "?type=1&page={$page}&rows=16",
                            'num'   => 0
                        ]);
                    }

                    for ($i = 0; $i < 5; $i++) {
                        go(function () use (&$urls) {
                            while (true) {
                                if (empty($urls)) {
                                    break;
                                }

                                $url = array_shift($urls);
                                try {
                                    $d = SaberGM::get($url['url'], [
                                        'timeout' => 60,
                                        'retry_time' => 5,
                                        'headers' => $this->header
                                    ]);
                                } catch (\Throwable $th) {
                                    if ($url['num'] > 3) {
                                        dump(1 . "_" . $th->getMessage() . "_" . $url);
                                        file_put_contents('goods.txt', json_encode([
                                            'url' => $url
                                        ], JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
                                    } else {
                                        $url['num']++;
                                        array_push($urls, $url);
                                    }
                                    continue;
                                }

                                $body = json_decode((string)$d->getBody(), true);
                                $data = $body['page']['list'];

                                for ($j = 0; $j < 10; $j++) {
                                    go(function () use (&$data) {
                                        while (true) {
                                            if (empty($data)) {
                                                break;
                                            }

                                            $d = array_shift($data);
                                            $url = 'http://ggzyjyzx.shandong.gov.cn/wssc/goods/' . $d['id'];
                                            App::getServer()->task([
                                                'name'      => Goods::class,
                                                'action'    => 'download',
                                                'param'     => [
                                                    'url' => $url,
                                                ],
                                                'finish'    => true
                                            ]);
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }



        echo 'use ' . (microtime(true) - $s) . ' s';
    }
}
