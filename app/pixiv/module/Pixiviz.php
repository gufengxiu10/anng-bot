<?php

declare(strict_types=1);

namespace app\pixiv\module;

use app\pixiv\Base;

class Pixiviz extends Base
{
    private $baseUri = 'https://pixiviz.pwp.app/api';

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
        return $this->send('get', '/v1/illust/rank', [
            'mode' => $mode,
            'date' => $date,
            // 'offset' => $offset,
            'page' => $page
        ]);
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
            'referer' => 'https://pixiviz.pwp.app/',
            'user-agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:30.0) Gecko/20100101 Firefox/30.0',
        ];
    }
}
