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

                if ($request->has('tag', 'param', true)) {
                    $query->where('tag_id', 'in', $request->param('tag'));
                }

                if ($request->has('isOriginal', 'param', true) && $request->param('isOriginal') >= 0) {
                    $query->where('is_original', $request->param('isOriginal'));
                }

                if ($request->has('isOnSale', 'param', true) && $request->param('isOnSale') >= 0) {
                    $query->where('is_release', $request->param('isOnSale'));
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

        if (!empty($content = Db::name('article_content')
            ->where('aid', $info->id)
            ->first())) {
            $content = $content->content;
        }
        $info->content = $content;

        return $info;
    }

    public function insert(RequestInterface $request)
    {
        $info = Db::name('article')->insert([
            'title'         => $request->param('title'),
            'subtitle'      => $request->param('subtitle'),
            'cat_id'        => $request->param('cat_id', 0),
            'is_original'   => $request->param('is_original', 1),
            'is_comment'    => $request->param('is_comment', 1),
            'is_password'   => $request->param('is_password', 0),
            'password'      => $request->param('password'),
            'is_release'    => $request->param('is_release', 1),
            'author'        => $request->param('author'),
            'url'           => $request->param('url'),
            'main_image'    => $request->param('main_image'),
            'create_time'   => time(),
            'update_time'   => time(),
        ]);

        if ($info && $request->has('content', 'param', true)) {
            Db::name('article_content')->insert([
                'aid'           => $info->id,
                'content'       => $request->param('content'),
                'update_time'   => time(),
                'create_time'   => time(),
            ]);
        }

        return $info ? true : false;
    }

    public function update($id, RequestInterface $request)
    {
        $info = Db::name('article')->where('id', $id)->update([
            'title'         => $request->param('title'),
            'subtitle'      => $request->param('subtitle'),
            'main_image'    => $request->param('main_image'),
            'cat_id'        => $request->param('cat_id', 0),
            'is_original'   => $request->param('is_original', 0),
            'is_comment'    => $request->param('is_comment', 0),
            'is_password'   => $request->param('is_password', 0),
            'password'      => $request->param('password'),
            'is_release'    => $request->param('is_release', 0),
            'author'        => $request->param('author'),
            'url'           => $request->param('url'),
            'tag_id'        => is_array(($tagId = $request->param('tag_id'))) ? implode(',', $tagId) : $tagId,
            'update_time'   => time(),
        ]);

        if (!$info) {
            throw new Exception('更新失败');
        }

        Db::name('article_content')->where('aid', $id)->update([
            'content' => $request->param('content'),
            'update_time' => time()
        ]);
    }
}
