<?php

namespace app\module\controller\admin\article;

use Anng\lib\contract\RequestInterface;
use Anng\lib\facade\Db;
use Anng\lib\facade\Request;
use app\BaseController;
use app\module\service\article\Index as ArticleIndex;

class Index extends BaseController
{
    /**
     * @name: 获得文章列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists(RequestInterface $request)
    {
        return $this->success((new ArticleIndex)->lists($request));
    }

    /**
     * @name: 获得文章信息
     * @param {*} $id
     * @author: ANNG
     * @return {*}
     */
    public function info($id)
    {
        return $this->success($this->service(ArticleIndex::class)->info($id));
    }

    /**
     * @name: 添加文章
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function add(RequestInterface $request)
    {
        $this->service(ArticleIndex::class)->insert($request);
        return $this->message('添加成功')->success();
    }

    /**
     * @name: 更新文章信息
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function update(RequestInterface $request, $id)
    {
        $this->service(ArticleIndex::class)->update($id, $request);

        return $this->message('更新成功')->success();
    }

    /**
     * @name: 设置文章密码
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function setPassword(RequestInterface $request, $id)
    {
        return $this->service(ArticleIndex::class)->update($id, [
            'password'      => $request->param('password')
        ]);
    }

    public function del($id)
    {
        if (empty($id)) {
            $this->message('参数错误')->error();
        }

        $res = Db::name('article')->where('id', $id)->delete();
        return $this->success();
    }
}
