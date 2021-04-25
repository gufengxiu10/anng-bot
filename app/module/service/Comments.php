<?php

namespace app\module\service;

use Anng\lib\facade\Db;
use Exception;

class Comments
{
    /**
     * @name: 全部评论
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists($type = 0)
    {
        return Db::name('comments')
            ->where('type', $type)
            ->select();
    }

    /**
     * @name: 获得文章相关的评论
     * @param {int} $id
     * @author: ANNG
     * @return {*}
     */
    public function articleLists(int $id)
    {
        return $this->lists(1);
    }

    /**
     * @name: 添加评论
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function insert($data)
    {
        if (!(!empty($data['pid']) && Db::name('comments')->where('id', $data['pid'])->count() > 0)) {
            throw new Exception('数据不存在');
        }
        return Db::name('comments')->insert($data);
    }
}
