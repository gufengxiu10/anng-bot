<?php

declare(strict_types=1);

namespace Anng\lib\contract;

interface RequestInterface
{
    /**
     * @name: 判断参数是否存在
     * @author: ANNG
     * @return bool
     */
    public function has($name, $type = 'param', $isEmpty = false): bool;

    /**
     * @name: 获得参数
     * @param {*} $name 获得指定参数，当为空是刚获得全部
     * @param {*} $default 设置默认值
     * @author: ANNG
     * @return mixed
     */
    public function param($name = '', $default = ''): mixed;


    public function file($name = ''): mixed;
}
