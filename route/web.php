<?php

use Anng\lib\facade\Route;
use app\controller\admin\Admin;
use app\module\controller\admin\Pixiv;
use app\module\controller\admin\article\Index as ArticleIndex;
use app\module\controller\admin\article\Cate as ArticleCate;
use app\controller\Test;


Route::group('api', function () {

    Route::get('bai', [Admin::class, 'list']);
    Route::get('dui', [Admin::class, 'list']);

    Route::get('download', [Test::class, 'download']);
    Route::get('list', [Test::class, 'list']);


    Route::group('article', function () {
        Route::group('cate', function () {
            Route::get('/', [ArticleCate::class, 'lists']);
            Route::post('', [ArticleCate::class, 'add']);
            Route::put('/:id', [ArticleCate::class, 'update']);
        });

        Route::get('/:id', [ArticleIndex::class, 'info']);
        Route::get('/bki/ul/:kjjj', [ArticleIndex::class, 'info']);
        Route::get('/', [ArticleIndex::class, 'lists']);
        Route::post('/add', [ArticleIndex::class, 'add']);
        Route::put('/:id', [ArticleIndex::class, 'update']);
        Route::delete('/:id', [ArticleCate::class, 'update']);
    });


    Route::group('pixiv', function () {
        Route::get('/', [Pixiv::class, 'lists']);
    });
});
