<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\App;
use Anng\lib\facade\Request;
use Anng\plug\pixiv\module\Pixiviz;
use app\BaseController;
use app\task\Pixiv as TaskPixiv;

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
