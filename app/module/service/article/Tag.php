<?php

namespace app\module\service\article;

use Anng\lib\facade\Db;

class Tag
{

    /**
     * @name: 标签列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return Db::name('article_label')->select();
    }

    /**
     * @name: 添加标签
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function create(array $data): mixed
    {
        return Db::name('article_label')->insert(array_merge([
            'create_time'   => time(),
            'update_time'   => time(),
        ], $data));
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
