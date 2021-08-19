<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\contract\RequestInterface;

use app\BaseController;
use Swlib\SaberGM;

class Pixiv extends BaseController
{
    public function lists(RequestInterface $request)
    {
        $date = $request->param('date', date('Y-m-d', strtotime('-2 day')));
        $page = $request->param('page',1);
        $res = SaberGM::get('https://pixiviz-api-us.pwp.link/v1/illust/rank?mode=day&date=' . $date . '&page=' . $page, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36 Edg/92.0.902.73',
                'Referer' => 'https://pixiviz.pwp.app/',
                "Content-Type:" => 'application/x-www-form-urlencoded'
            ],
            'timeout' => 30
        ]);
        dump('https://pixiviz-api-us.pwp.link/v1/illust/rank?mode=day&date=' . $date . '&page=1');
        return $this->success($res->getParsedJsonArray());
        dump($res->getParsedJsonArray());
    }

    // public function getImg()
    // {
    //     Response::header('Content-type', 'image/png');
    //     $url = Request::param('url');

    //     if (!empty($url)) {
    //         $key = 'img_' . $url;
    //         if (Cache::has($key)) {
    //             return Cache::get($key);
    //         }

    //         $client = Saber::create([
    //             'headers' => [
    //                 'referer'       => 'https://pixiviz.pwp.app/',
    //                 'user-agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36 Edg/90.0.818.42'
    //             ],
    //             'timeout'   => 30
    //         ]);

    //         $imgRef = $client->get($url);
    //         $body = $imgRef->getBody()->getContents();
    //         Cache::set($key, $body, 3600);
    //         return $body;
    //     }
    //     return file_get_contents(APp::rootPath('public/images/2017-09-04/64758647_p0.png'));
    // }
}
