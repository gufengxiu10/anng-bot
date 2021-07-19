<?php

namespace app\module\service\article;

use Anng\lib\contract\RequestInterface;
use Anng\lib\facade\Db;
use Exception;

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
        return Db::name('article_cate')->get();
    }

    public function info(int $id)
    {
        $info = Db::name('article_cate')
            ->where('id', $id)
            ->first();

        if (!$info) {
            throw new Exception('信息不存在');
        }

        return $info;
    }

    public function create(RequestInterface $request)
    {
        $info = Db::name('article_cate')->insert([
            'name'          => $request->param('name'),
            'create_time'   => time(),
            'update_time'   => time()
        ]);

        return $info;
    }

    public function update(RequestInterface $request)
    {
        $info = Db::name('article_cate')
            ->where('id', $request->param('id'))
            ->update([
                'name'          => $request->param('name'),
                'update_time'   => time()
            ]);

        return $info;
    }

    public function del(int $id)
    {
        $info = $this->info($id);
        Db::name('article_cate')->where('id', $info->id)->delete();
    }
}
