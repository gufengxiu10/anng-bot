<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\Request;
use app\BaseController;
use app\module\service\Comments as ServiceComments;

class Comments extends BaseController
{
    public function lists()
    {
        return $this->service(ServiceComments::class)->lists();
    }

    public function add()
    {
        return $this->service(ServiceComments::class)->insert([
            'content'       => Request::param('content'),
            'pid'           => Request::param('pid', 0),
            'type'          => 0,
            'update_time'   => time(),
            'create_time'   => time()
        ]);
    }
}
