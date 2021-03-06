<?php

declare(strict_types=1);

namespace app\crontab;

use Anng\lib\crontab\Base;
use Anng\lib\facade\Redis;
use Anng\lib\facade\Table;
use app\cq\Cq;
use app\cq\module\Message;
use Swlib\SaberGM;

class ToDayPrice extends Base
{
    public function run()
    {
        $group = [415446505];
        $group = [415446505, 93958924];
        if (!Redis::exists('lolicon')) {
            $res = SaberGM::get('https://api.lolicon.app/setu/' . '?' . http_build_query([
                'apikey' => '32906725601ba5cf18c942',
                'r18' => 0,
                'num' => 10
            ]), [
                'timeout' => 20
            ]);

            $data = json_decode($res->getBody()->getContents(), true);
            Redis::setex('lolicon-all', 3600, json_encode($data, JSON_UNESCAPED_UNICODE));
            Redis::setex('lolicon', 3600, json_encode($data['data'], JSON_UNESCAPED_UNICODE));
        }

        $data = Redis::get('lolicon');
        $data = json_decode($data, true);
        $url = array_shift($data);
        try {
            $res = SaberGM::get($url['url'], [
                'headers' => [
                    'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0',
                    ':authority' => 'pixiv-image-jp.pwp.link',
                    ':method' => 'GET',
                    ':path' => $url['url'],
                    ':scheme' => 'https',
                ],
                'timeout' => 20,
                'retry_time' => 3
            ]);

            if ($res->getStatusCode() != 200) {
                return;
            }

            if (!file_exists('/images/t')) {
                mkdir('/images/t', 0777, true);
            }
            $fileName = '/images/t/' . substr($url['url'], strrpos($url['url'], '/') + 1);
            $wd = fopen($fileName, 'w');

            $body = $res->getBody();
            while (!$body->eof()) {
                $content = $body->read(1024 * 200);
                fwrite($wd, $content);
            }
            fclose($wd);
            foreach (Table::getinstance('fd') as $val) {
                if ($val['isBot'] == 1) {
                    foreach ($group as $id) {
                        $this->server->push($val['fd'], (new Message)->sendGroup((string)$id, (new Cq)->image('file://' . $fileName)));
                    }
                }
            }
        } catch (\Throwable $th) {
            $this->run();
            return;
        }

        if (empty($data)) {
            Redis::del('lolicon');
        } else {
            Redis::setex('lolicon', 3600, json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    }
}
