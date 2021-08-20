<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\contract\RequestInterface;
use Anng\lib\facade\Response as FacadeResponse;
use app\BaseController;
use Swlib\Http\Response;
use Swlib\Saber;
use Swlib\SaberGM;

class Pixiv extends BaseController
{
    public function lists(RequestInterface $request)
    {
        $date = $request->param('date', date('Y-m-d', strtotime('-2 day')));
        $page = $request->param('page', 1);
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

    public function getImg(RequestInterface $request)
    {
        // FacadeResponse::header('Content-type', 'image/png');
        $url = $request->param('url');

        $client = Saber::create([
            'headers' => [
                'referer'       => 'https://pixiviz.pwp.app/',
                'user-agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36 Edg/90.0.818.42'
            ],
            'timeout'   => 30
        ]);

        $imgRef = $client->get($url);
        FacadeResponse::header('content-type', $imgRef->getHeader('content-type')[0]);
        $body = $imgRef->getBody()->getContents();
        return $body;
    }
}
