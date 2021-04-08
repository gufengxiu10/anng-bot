<?php

use Anng\lib\facade\Route;
use app\controller\admin\Admin;
use app\controller\admin\article\Index as ArticleIndex;
use app\controller\Test;

Route::group('api', function () {

    Route::get('bai', [Admin::class, 'list']);
    Route::get('dui', [Admin::class, 'list']);

    Route::get('download', [Test::class, 'download']);
    Route::get('list', [Test::class, 'list']);
    Route::get('check', [Test::class, 'check']);

    Route::group('article', function () {
        Route::get('/', [ArticleIndex::class, 'list']);
    });
});
