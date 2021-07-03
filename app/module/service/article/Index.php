<?php

namespace app\module\service\article;

use Anng\lib\contract\RequestInterface;
use Anng\lib\facade\Db;
use Exception;

class Index
{
    public function lists(RequestInterface $request)
    {
        $data = Db::name('article')
            ->where(function ($query) use ($request) {
                if ($request->has('cate', 'param', true)) {
                    $query->where('cat_id', $request->param('cate'));
                }

                if ($request->has('key', 'param', true) && $request->has('keyValue', 'param', true)) {
                    switch ($request->param('key')) {
                        case 'id':
                            $query->where('id', $request->param('keyValue'));
                            break;
                        case 'name':
                            $query->where('title', 'like', "%{$request->param('keyValue')}%");
                            break;
                    }
                }
            })
            ->get()
            ->map(function ($item) {
                if ($item['cat_id'] >  0 && Db::name('article_cate')->where('id', $item['cat_id'])->exists()) {
                    $item['cate'] = Db::name('article_cate')->where('id', $item['cat_id'])->first();
                }

                if ($item['tag_id'] > 0) {
                    $item['tag'] = Db::name('tag')->where('id', 'in', $item['tag_id'])->get();
                }
                dump($item);

                return $item;
            });

        return $data;
    }

    public function info($id)
    {
        $info = Db::name('article')
            ->first($id);

        if ($info->isEmpty()) {
            throw new Exception('数据不存在');
        }

        $info->tag_id = ($tagIds = explode(',', $info['tag_id'])) ? array_map(fn ($item) => (int)$item, array_filter($tagIds)) : [];
        if (Db::name('article_content')->where('aid', $info->id)->exists()) {
            $info['content'] = Db::name('article_content')->where('aid', $info->id)->first();
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
