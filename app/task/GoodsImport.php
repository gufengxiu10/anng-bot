<?php

declare(strict_types=1);

namespace app\task;

use Anng\lib\facade\App;
use Anng\lib\facade\Db;
use Swoole\Coroutine\WaitGroup;
use Symfony\Component\Finder\Finder;

class GoodsImport
{
    public function run()
    {
        $startId = 324;
        $list = Db::name('goods')
            ->where('brand_id', 'in', [165, 3519])
            // ->limit(10)
            ->select();

        $sql = '';
        $copy = [];
        $name = 'zztcsm_cn';
        $wg = new WaitGroup;
        $wg->add(count($list));
        foreach ($list as $key => $info) {
            go(function () use (&$sql, $wg, $info, &$startId, $key, &$copy, $name) {
                $infoArr = $info;
                unset($infoArr['product_area_id']);
                $fields = array_keys($infoArr);

                $infoArr['goods_id'] = $startId + $key;
                if (empty($infoArr['cat_id'])) {
                    $infoArr['cat_id'] = 0;
                }
                $value = array_values($infoArr);
                array_walk($fields, fn (&$item) => $item = "`{$item}`");
                array_walk($value, function (&$item) {
                    if (!is_numeric($item)) {
                        if (empty($item)) {
                            $item = "\"\"";
                        } elseif (is_string($item)) {
                            $item = addslashes($item);
                            $item = "\"{$item}\"";
                        }
                    }
                });


                $fieldsString = implode(',', $fields);
                $valueString = implode(',', $value);
                $sql .= "INSERT INTO `{$name}`.`tp_goods`({$fieldsString}) VALUES ({$valueString});" . "\n";
                $path = App::rootPath('public/execl/goods/thumb/');
                $goodsPath = $path . $info['goods_id'];
                $newPath = App::rootPath('runtime/execl/goods/thumb/');

                go(function () use ($goodsPath, $infoArr, $newPath, &$copy, $wg) {
                    if (file_exists($goodsPath)) {
                        $finder = new Finder;
                        $finder->in($goodsPath);
                        foreach ($finder->files() as $filed) {
                            $fileName = $filed->getFilename();
                            $fileName = explode('_', strstr($fileName, '.', true));
                            foreach ($fileName as &$v) {
                                if (is_numeric($v)) {
                                    $v = $infoArr['goods_id'];
                                    break;
                                }
                            }
                            $fileName = implode('_', $fileName) . '.' . $filed->getExtension();
                            $originFile = $goodsPath . '/' . $filed->getFilename();
                            $newPathFile = $newPath . $infoArr['goods_id'];
                            $newFile = $newPathFile . '/' . $fileName;
                            $copy[] = [$originFile, $newFile];
                        }
                    }
                });


                go(function () use ($info, &$copy, $wg, &$sql, $name, $infoArr) {
                    $imagesAll = Db::name('goods_images')->where('goods_id', $info['goods_id'])->select();
                    foreach ($imagesAll as $value) {

                        $imageOldPathFile = App::rootPath(str_replace('/Public/upload', 'public/execl', $value['image_url']));
                        $imageNewPathFile = str_replace('public/execl', 'runtime/execl', $imageOldPathFile);
                        if (!file_exists($imageOldPathFile)) {
                            continue;
                        }
                        $value['goods_id'] = $infoArr['goods_id'];
                        unset($value['img_sort']);
                        unset($value['img_id']);
                        $fields = array_keys($value);

                        if (empty($infoArr['cat_id'])) {
                            $infoArr['cat_id'] = 0;
                        }
                        $value = array_values($value);
                        array_walk($fields, fn (&$item) => $item = "`{$item}`");
                        array_walk($value, function (&$item) {
                            if (!is_numeric($item)) {
                                if (empty($item)) {
                                    $item = "\"\"";
                                } elseif (is_string($item)) {
                                    $item = addslashes($item);
                                    $item = "\"{$item}\"";
                                }
                            }
                        });

                        $fieldsString = implode(',', $fields);
                        $valueString = implode(',', $value);

                        $sql .= "INSERT INTO `{$name}`.`tp_goods_images`({$fieldsString}) VALUES ({$valueString});" . "\n";
                        $copy[] = [$imageOldPathFile, $imageNewPathFile];
                    }
                });


                if ($info['goods_content']) {
                    go(function () use ($info, &$copy, $wg) {
                        preg_match_all("/<img(.*?)src=\"(.*?)\"(.*?)>/", $info['goods_content'], $matchs);
                        foreach ($matchs[0] as $v) {
                            $start = strpos($v, '"');
                            $newV = substr($v, $start + 1);
                            $newV = strstr($newV, '"', true);
                            $vOriginFile =  App::rootPath(str_replace('/Public/upload', 'public/execl', $newV));
                            if (!file_exists($vOriginFile)) {
                                continue;
                            }
                            $vNewFile = str_replace('public/execl', 'runtime/execl', $vOriginFile);
                            $copy[] = [$vOriginFile, $vNewFile];
                        }
                    });
                }

                $wg->done();
            });
        }
        $wg->wait();

        for ($i = 0; $i < 50; $i++) {
            go(function () use (&$copy, $i) {
                while (true) {
                    if (empty($copy)) {
                        break;
                    }
                    [$origin, $new] = array_shift($copy);

                    dump(count($copy));
                    $imageNewPath = substr($new, 0, strrpos($new, '/'));
                    if (!file_exists($imageNewPath)) {
                        mkdir($imageNewPath, 0777, true);
                    }

                    copy($origin, $new);
                    dump('复制成功-' . $origin . '-' . $new);
                }
            });
        }
        file_put_contents(App::rootPath('runtime/execl/1.sql'), $sql);

        return '成功';
    }
}
