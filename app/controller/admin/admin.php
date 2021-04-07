<?php

namespace app\controller\admin;

use Anng\lib\facade\Table;

class Admin
{
    public function list()
    {
        dump(Table::name('fd')->count());
    }

    public function list2()
    {
        dump('list2');
    }

    public function list3()
    {
        dump('list3');
    }
}
