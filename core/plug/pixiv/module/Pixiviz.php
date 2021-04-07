<?php

declare(strict_types=1);

namespace Anng\plug\pixiv\module;

use Anng\lib\facade\Cache;
use Anng\plug\pixiv\Base;

class Pixiviz extends Base
{
    private $baseUri = 'https://pixiviz.pwp.Anng\plug/api';

    /**
     * @name: 日榜
     * @param {*} $date
     * @param {*} $page
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 10:25:38
     * @return {*}
     */
    public function day($date, $page = 1)
    {
        return $this->rank('day', $date, $page);
    }

    /**
     * @name: 周榜
     * @param {*} $date
     * @param {*} $page
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 10:26:38
     * @return {*}
     */
    public function week($date, $page = 1)
    {
        return $this->rank('week', $date, $page);
    }

    public function month($date, $page = 1)
    {
        return $this->rank('month', $date, $page);
    }

    /**
     * @name: 榜单
     * @param {*} $date
     * @param {*} $page
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 10:25:38
     * @return {*}
     */
    public function rank($mode, $date,  $page = 1)
    {
        $key = "Pixiviz_{$mode}_{$date}";
        if (!Cache::has($key)) {
            $data = $this->send('get', '/v1/illust/rank', [
                'mode' => $mode,
                'date' => $date,
                // 'offset' => $offset,
                'page' => $page
            ]);

            $data = $data->getParsedJsonArray();
            Cache::set($key, $data, 3600);
        }

        return Cache::get($key);
    }

    /**
     * @name: 获得用户图片
     * @param {*} $id
     * @param {*} $page
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 11:27:13
     * @return {*}
     */
    public function userIllusts($id, $page = 1)
    {
        return $this->send('get', '/v1/user/illusts', [
            'id' => $id,
            'page' => $page
        ]);
    }

    /**
     * @name: 获得用户信息
     * @param {*} $id
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-25 11:28:00
     * @return {*}
     */
    public function userDetail($id)
    {
        return $this->send('get', '/v1/user/detail', [
            'id' => $id,
        ]);
    }

    public function getBaseUri()
    {
        return $this->baseUri;
    }

    public function getHeaders()
    {
        return [
            'referer' => 'https://pixiviz.pwp.Anng\plug/',
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0',
        ];
    }
}
