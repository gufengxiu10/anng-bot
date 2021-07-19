<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\contract\RequestInterface;
use app\BaseController;
use app\module\service\Comments as ServiceComments;

class Comments extends BaseController
{
    public function lists()
    {
        return $this->service(ServiceComments::class)->listsChilder();
        return '';
    }

    public function add(RequestInterface $request)
    {
        dump($request->file()->getExtension());
        return '';
        return $this->service(ServiceComments::class)->insert([
            'content'       => $request->param('content'),
            'pid'           => $request->param('pid', 0),
            'type'          => 0,
            'update_time'   => time(),
            'create_time'   => time()
        ]);
    }
}
