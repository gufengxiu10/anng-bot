<?php

declare(strict_types=1);

namespace app\task;

use Anng\lib\Collection;
use Anng\lib\facade\App;
use Swlib\Saber;
use Swoole\Coroutine;

class Gov
{
    public function login()
    {
        # code...
    }

    public function download($data)
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $client = Saber::create([
            'headers' => [
                'referer'       => 'https://pixiviz.pwp.app/',
                'user-agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36 Edg/90.0.818.42'
            ],
            'timeout'   => 30
        ]);

        for ($i = 0; $i <= 4; $i++) {
            go(function () use (&$data, $client) {
                while (true) {
                    dump(count($data));
                    if (empty($data)) {
                        break;
                    }
                    $img = array_shift($data);
                    $fileName = substr($img['img'], strrpos($img['img'], '/') + 1);
                    try {
                        $imgRef = $client->get($img['img']);
                        $body = $imgRef->getBody();
                    } catch (\Throwable $th) {
                        dump('文件名：' . $fileName . '_' . $th->getMessage());
                        continue;
                    }

                    $bashPath = App::rootPath('public/images/' . $img['date']);
                    if (!file_exists($bashPath)) {
                        mkdir($bashPath, 0777, true);
                    }

                    $file = $bashPath . '/' . $fileName;
                    $fd = fopen($file, 'w+');
                    while (!$body->eof()) {
                        $read = $body->read(1024 * 1024);
                        fwrite($fd, $read);
                    }

                    fclose($fd);
                    dump('文件名：' . $fileName . '_下载成功');
                    Coroutine::sleep(1);
                }
            });
        }
    }
}
