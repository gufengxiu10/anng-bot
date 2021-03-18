<?php

namespace app\controller\admin;

use Anng\lib\annotations\module\Messages;

class Admin
{
    public function __construct()
    {
        dump(1000);
    }

    #[Messages(['key' => '你好', 'alias' => ['wq', 'find']])]
    public function list()
    {
        dump('list1');
    }

    #[Messages(['key' => '来一图', 'alias' => ['ki']])]
    public function list2()
    {
        dump('list2');
    }

    public function list3()
    {
        dump('list3');
    }
}
