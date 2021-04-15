<?php

namespace app\module\controller\admin\article;

use Anng\lib\facade\Request;
use Anng\plug\pixiv\module\Pixiviz;
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

    /**
     * @name: 获得文章信息
     * @param {*} $id
     * @author: ANNG
     * @return {*}
     */
    public function info($id)
    {
        return $this->service(ArticleIndex::class)->info($id);
    }

    /**
     * @name: 添加文章
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function add()
    {
        return $this->service(ArticleIndex::class)->insert([
            'title'         => Request::param('title'),
            'subtitle'      => Request::param('subtitle'),
            'create_time'   => time(),
            'update_time'   => time(),
        ]);
    }

    /**
     * @name: 更新文章信息
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function update()
    {
        return (new Pixiviz)->day('2021-04-10');
    }
}
