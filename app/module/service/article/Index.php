<?php

namespace app\module\service\article;

use Anng\lib\facade\Db;
use Exception;

class Index
{
    public function lists($page, $limit)
    {
        $data = Db::name('article')
            ->limit($page, $limit)
            ->select();
        
        return $data;
    }

    public function info($id)
    {
        $info = Db::name('article')
            ->find($id);

        if ($info->isEmpty()) {
            throw new Exception('数据不存在');
        }

        return $info;
    }

    public function insert($data)
    {
        $info = Db::name('article')->insert([
            'title'         => $data['title'],
            'subtitle'      => $data['subtitle'],
            'create_time'   => time(),
            'update_time'   => time(),
        ]);

        if ($info && isset($data['content'])) {
            Db::name('article_content')->insert([
                'aid'           => $info['id'],
                'content'       => $data['content'],
                'update_time'   => time(),
                'create_time'   => time(),
            ]);
        }
    }

    public function update($id, $data)
    {
        Db::name('article')->where('id', $id)->update($data);
        if (isset($data['content'])) {
            Db::name('article')->where('id', $id)->update([]);
        }
        return;
    }
}
