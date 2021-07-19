<?php

use Anng\lib\facade\Route;
use app\module\controller\admin\Admin;
use app\module\controller\admin\Pixiv;
use app\module\controller\admin\article\{
    Index as ArticleIndex,
    Cate as ArticleCate,
    Tag as ArticleTag,
};
use app\module\controller\admin\Comments;
use app\module\controller\admin\Other;
use app\module\controller\Common;

Route::group('api', function () {

    Route::post('upload', [Common::class, 'upload']);

    Route::post('bai', [Other::class, 'import']);
    // Route::get('bai2', [Pixiv::class, 'download']);

    Route::group('admin', function () {
        Route::post('add', [Admin::class, 'test']);
    });

    Route::group('article', function () {
        Route::group('cate', function () {
            Route::get('/', [ArticleCate::class, 'lists']);
            Route::post('', [ArticleCate::class, 'add']);
            Route::put('/:id', [ArticleCate::class, 'update']);
        });


        Route::group('tag', function () {
            Route::get('/', [ArticleTag::class, 'lists']);
            Route::post('/', [ArticleTag::class, 'add']);
        });

        Route::get('/:id', [ArticleIndex::class, 'info']);
        Route::get('/', [ArticleIndex::class, 'lists']);
        Route::post('/add', [ArticleIndex::class, 'add']);
        Route::put('/:id', [ArticleIndex::class, 'update']);
        Route::delete('/:id', [ArticleIndex::class, 'del']);
    });


    Route::group('pixiv', function () {
        Route::get('/img', [Pixiv::class, 'getImg']);
        Route::get('/', [Pixiv::class, 'lists']);
    });


    Route::group('comments', function () {
        Route::get('/', [Comments::class, 'lists']);
        Route::post('/', [Comments::class, 'add']);
    });
});
