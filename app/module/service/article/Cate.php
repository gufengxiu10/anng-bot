<?php

namespace app\module\service\article;

use Anng\lib\facade\Db;

class Cate
{
    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function lists()
    {
        return Db::name('article_cate')->select();
    }

    public function create($data)
    {
        $info = Db::name('article_cate')->insert([
            'name'          => $data['name'],
            'create_time'   => time(),
            'update_time'   => time()
        ]);

        return $info;
    }

    public function update($id, $data)
    {
        $info = Db::name('article_cate')->where('id', $id)->update($data);
        return $info;
    }

    public function del(int $id)
    {
        Db::name('article_cate')->where('id', $id)->delete();
    }
}
