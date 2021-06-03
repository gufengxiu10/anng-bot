<?php

namespace app\module\controller\admin\article;

use Anng\lib\facade\Request;
use app\BaseController;
use app\module\requests\Article as ArticleRequest;
use app\module\service\article\Index as ArticleIndex;

class Index extends BaseController
{
    /**
     * @name: 获得文章列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return $this->success($this->service(ArticleIndex::class)
            ->lists(Request::param('page', 1), Request::param('limit', 10)));
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
            'cat_id'        => Request::param('cat_id'),
            'is_original'   => Request::param('is_original'),
            'is_comment'    => Request::param('is_comment'),
            'is_password'   => Request::param('is_password'),
            'password'      => Request::param('password'),
            'is_release'    => Request::param('is_release'),
            'author'        => Request::param('author'),
            'url'           => Request::param('url'),
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
    public function update($id)
    {
        return $this->service(ArticleIndex::class)->update($id, [
            'title'         => Request::param('title'),
            'subtitle'      => Request::param('subtitle'),
            'cat_id'        => Request::param('cat_id'),
            'is_original'   => Request::param('is_original'),
            'is_comment'    => Request::param('is_comment'),
            'is_password'   => Request::param('is_password'),
            'password'      => Request::param('password'),
            'is_release'    => Request::param('is_release'),
            'author'        => Request::param('author'),
            'url'           => Request::param('url'),
            'update_time'   => time(),
        ]);
    }

    /**
     * @name: 设置文章密码
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function setPassword($id)
    {
        return $this->service(ArticleIndex::class)->update($id, [
            'password'      => Request::param('password')
        ]);
    }
}
