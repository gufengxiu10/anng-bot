<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\App;
use Anng\lib\facade\Cache;
use Anng\lib\facade\Request;
use Anng\lib\facade\Response;
use Anng\plug\pixiv\module\Pixiviz;
use app\BaseController;
use app\task\Pixiv as TaskPixiv;
use Swlib\Saber;

class Pixiv extends BaseController
{
    public function lists()
    {
        $date = Request::param('date', date('Y-m-d', strtotime('-3 day')));
        $data = (new Pixiviz)->day($date);
        $origin = [];
        foreach ($data['illusts'] as $key => $val) {
            if ($val['page_count'] > 1) {
                $originImg = $val['meta_pages'][0]['image_urls']['original'];
            } else {
                $originImg = $val['meta_single_page']['original_image_url'];
            }

            // $originImg = str_replace('https://i.pximg.net', '', $originImg);
            $originImg = str_replace('https://i.pximg.net', 'https://pixiv-image-jp.pwp.link', $originImg);
            $data = [
                'img'   => $originImg,
                'date'  => $date,
                'data'   => $val
            ];
            array_push($origin, $data);
        }

        return $origin;
    }

    public function getImg()
    {
        Response::header('Content-type', 'image/png');
        $url = Request::param('url');

        if (!empty($url)) {
            $key = 'img_' . $url;
            if (Cache::has($key)) {
                return Cache::get($key);
            }

            $client = Saber::create([
                'headers' => [
                    'referer'       => 'https://pixiviz.pwp.app/',
                    'user-agent'    => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.72 Safari/537.36 Edg/90.0.818.42'
                ],
                'timeout'   => 30
            ]);

            $imgRef = $client->get($url);
            $body = $imgRef->getBody()->getContents();
            Cache::set($key, $body, 3600);
            return $body;
        }
        return file_get_contents(APp::rootPath('public/images/2017-09-04/64758647_p0.png'));
    }

    public function download()
    {
        $origin = $this->lists();
        App::getServer()->task([
            'name'      => TaskPixiv::class,
            'action'    => 'download',
            'param'     => $origin,
            'finish'    => true
        ]);

        return '完成';
    }
}
