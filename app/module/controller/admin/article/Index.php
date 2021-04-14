<?php

namespace app\module\controller\admin\article;

use Anng\lib\facade\Request;
use app\module\Controller;
use app\module\service\article\Index as ArticleIndex;

class Index extends Controller
{
    /**
     * @name: 获得文章列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return $this->service(ArticleIndex::class)
            ->lists(Request::param('page', 1), Request::param('limit', 10));
    }

    public function add()
    {
        return $this->service(ArticleIndex::class)->insert([
            'title'         => Request::param('title'),
            'subtitle'      => Request::param('subtitle'),
            'create_time'   => time(),
            'update_time'   => time(),
        ]);
    }
}
