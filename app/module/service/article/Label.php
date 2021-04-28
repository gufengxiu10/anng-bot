<?php

namespace app\module\service\article;

use Anng\lib\facade\Db;

class Label
{
    /**
     * @name: 标签列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return Db::name('article_cate')->select();
    }

    /**
     * @name: 添加标签
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function add(array $data): mixed
    {
        return Db::name('article_label')->insert($data);
    }

    /**
     * @name: 删除标签
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function del(int $id)
    {
        return Db::name('article_label')->where('id', $id)->delete();
    }
}
