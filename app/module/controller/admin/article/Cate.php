<?php

namespace app\module\controller\admin\article;

use Anng\lib\contract\RequestInterface;
use Anng\lib\facade\Container;
use Anng\lib\facade\Request;
use app\BaseController;
use app\module\service\article\Cate as ArticleCate;

class Cate extends BaseController
{
    /**
     * @name: 分类列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return $this->success($this->service(ArticleCate::class)->lists());
    }

    /**
     * @name: 分类添加
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function add(RequestInterface $request)
    {
        return $this->success($this->service(ArticleCate::class)->create($request));
    }

    /**
     * @name: 分类更新
     * @param {*} $id
     * @author: ANNG
     * @return {*}
     */
    public function update(RequestInterface $request, $id)
    {
        $this->service(ArticleCate::class)->update($request, $id);
        return $this->success();
    }

    /**
     * @name: 分类删除
     * @param {*} $id
     * @author: ANNG
     * @return {*}
     */
    public function del($id)
    {
        $this->service(ArticleCate::class)->del($id);
        return $this->success();
    }
}
