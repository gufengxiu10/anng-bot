<?php

namespace app\module\controller\admin\article;

use Anng\lib\facade\Request;
use app\module\Controller;
use app\module\service\article\Cate as ArticleCate;

class Cate extends Controller
{
    public function lists()
    {
        return $this->service(ArticleCate::class)->lists();
    }

    public function add()
    {
        return $this->service(ArticleCate::class)->create([
            'name' => Request::param('name'),
        ]);
    }

    public function update($id)
    {
        return $this->service(ArticleCate::class)->update($id, [
            'name' => Request::param('name'),
        ]);
    }
}
