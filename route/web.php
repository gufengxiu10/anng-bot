<?php

use Anng\lib\facade\Route;
use app\controller\admin\Admin;
use app\controller\Test;

Route::get('/dui', [Admin::class, 'list']);

Route::get('download', [Test::class, 'download']);
