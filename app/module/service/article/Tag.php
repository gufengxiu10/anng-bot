<?php

namespace app\module\service\article;

use Anng\lib\contract\RequestInterface;
use Anng\lib\facade\Db;
use Exception;

class Tag
{
    public function info(int $id)
    {
        $info = Db::name('article_label')->where('id', $id)->first();

        if (!$info) {
            throw new Exception('信息不存在');
        }

        return $info;
    }

    /**
     * @name: 标签列表
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return Db::name('article_label')->get();
    }

    /**
     * @name: 添加标签
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function create(RequestInterface $request): mixed
    {
        return Db::name('article_label')->insert([
            'name'          => $request->param('name'),
            'create_time'   => time(),
            'update_time'   => time()
        ]);
    }

    /**
     * @name: 添加标签
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function update(RequestInterface $request): mixed
    {
        return Db::name('article_label')->update([
            'name'          => $request->param('name'),
            'update_time'   => time()
        ]);
    }

    /**
     * @name: 删除标签
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function del(int $id)
    {
        $info = $this->info($id);
        return Db::name('article_label')->where('id', $info->id)->delete();
    }
}
