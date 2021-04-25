<?php

use Anng\lib\facade\Route;
use app\module\controller\admin\Pixiv;
use app\module\controller\admin\article\Index as ArticleIndex;
use app\module\controller\admin\article\Cate as ArticleCate;
use app\module\controller\admin\Comments;
use app\module\controller\admin\Other;

Route::group('api', function () {

    Route::get('bai', [Other::class, 'import']);
    Route::get('bai2', [Pixiv::class, 'download']);

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
        Route::get('/img', [Pixiv::class, 'getImg']);
        Route::get('/', [Pixiv::class, 'lists']);
    });


    Route::group('comments', function () {
        Route::get('/', [Comments::class, 'lists']);
        Route::post('/', [Comments::class, 'add']);
    });
});
