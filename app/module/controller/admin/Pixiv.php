<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\Request;
use Anng\plug\pixiv\module\Pixiviz;
use app\module\Controller;

class Pixiv extends Controller
{
    public function lists()
    {
        $data = (new Pixiviz)->day(Request::param('date', date('Y-m-d', strtotime('-3 day'))));
        $origin = [];
        foreach ($data['illusts'] as $key => $val) {
            if ($val['page_count'] > 1) {
                $originImg = $val['meta_pages'][0]['image_urls']['original'];
            } else {
                $originImg = $val['meta_single_page']['original_image_url'];
            }

            $originImg = str_replace('https://i.pximg.net', '', $originImg);
            // $originImg = str_replace('https://i.pximg.net', 'https://pixiv-image-jp.pwp.link', $originImg);
            $data = [
                'img' => $originImg,
                'data' => $val
            ];
            array_push($origin, $data);
        }

        return $origin;
    }
}
