<?php

use Anng\lib\facade\Route;
use app\controller\admin\Admin;

Route::get('/dui', [Admin::class, 'list']);

Route::post('api/bai');
