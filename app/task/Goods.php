<?php

declare(strict_types=1);

namespace app\task;

use Anng\lib\Collection;
use Swlib\SaberGM;

class Goods
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


    public function download($data)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        try {
            $res = SaberGM::get($data['url'], [
                'timeout' => 120,
                'retry_time' => 5,
                'headers' => $this->header
            ]);
        } catch (\Throwable $th) {
            file_put_contents('info.txt', json_encode([
                'url' => $data['url']
            ], JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND);
            return;
        }
        $body = (string)$res->getBody();
        $nd = json_decode($body, true);
        file_put_contents('1.txt', $body . "\n", FILE_APPEND);
    }
}
