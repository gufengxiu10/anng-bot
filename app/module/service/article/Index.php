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

        $info['tag_id'] = ($tagIds = explode(',', $info['tag_id'])) ? array_map(fn ($item) => (int)$item, array_filter($tagIds)) : [];
        $content = '';
        if ($contentInfo = Db::name('article_content')->where('aid', $info->id)->find()) {
            $content = $contentInfo->content;
        }

        $info['content'] = $content;

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
        $info = Db::name('article')->where('id', $id)->update($data);
        if (!$info) {
            throw new Exception('更新失败');
        }

        Db::name('article_content')->where('aid', $id)->update([
            'content' => $data['content'],
            'update_time' => time()
        ]);
    }
}
