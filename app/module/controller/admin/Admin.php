<?php

declare(strict_types=1);

namespace app\module\controller\admin;

use Anng\lib\facade\Request;
use app\BaseController;
use app\module\service\admin\Admin as AdminAdmin;

class Admin extends BaseController
{
    public function test()
    {
        if (!Request::has('name', true)) {
            throw new \Exception('name参数必须有');
        }

        if (!Request::has('password', true)) {
            throw new \Exception('password参数必须有');
        }
        $this->service(AdminAdmin::class)->add([
            'name'  => Request::param('name'),
            'password'  => Request::param('password')
        ]);
    }
}
