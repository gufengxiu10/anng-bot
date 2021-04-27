<?php

namespace app\module\service;

use Anng\lib\facade\Db;
use Anng\lib\Service;
use Exception;

class Comments extends Service
{
    public function info(int $id)
    {
        $info = Db::name('comments')
            ->where('id', $id)
            ->find();

        if (!$info) {
            throw new Exception('评论不存在');
        }

        return $info;
    }

    /**
     * @name: 全部评论
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists($type = 0, $pid = null)
    {
        return Db::name('comments')
            ->where('type', $type)
            ->where(fn ($db) => !($pid > 0) ? $db->where('pid', 0) : $db->where('pid', $pid))
            ->select();
    }

    /**
     * @name: 全部评论
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function listsChilder($type = 0, $pid = null)
    {
        return Db::name('comments')
            ->where('type', $type)
            ->where(fn ($db) => !($pid > 0) ? $db->where('pid', 0) : $db->where('pid', $pid))
            ->select()
            ->each(fn ($item) => $item->add('childer', $this->listsChilder($type, $item->id)->toArray(), true));
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
        if (!empty($data['pid']) && Db::name('comments')->where('id', $data['pid'])->count() <= 0) {
            throw new Exception('数据不存在');
        }
        return Db::name('comments')->insert($data);
    }

    /**
     * @name: 评论删除
     * @param {int} $id
     * @author: ANNG
     * @return {*}
     */
    public function del(int $id): void
    {
        $info = $this->info($id);
        if (Db::name('comments')->where('pid', $info->id)->count() > 0) {
            throw new Exception('有子评论不能进行删除');
        }
        Db::name('comments')->where('id', $info->id)->delete();
    }
}
