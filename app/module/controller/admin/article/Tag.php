<?php

namespace app\module\controller\admin\article;

use Anng\lib\facade\Request;
use app\BaseController;
use app\module\service\article\Tag as ArticleTag;

class Tag extends BaseController
{

    public function __construct()
    {
        $this->service = ArticleTag::class;
    }

    /**
     * @name: 分类列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return $this->success($this->service()->lists());
    }

    /**
     * @name: 分类添加
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function add()
    {
        return $this->service()->create([
            'name' => Request::param('name'),
        ]);
    }


    /**
     * @name: 分类删除
     * @param {*} $id
     * @author: ANNG
     * @return {*}
     */
    public function del($id)
    {
        # code...
    }
}
