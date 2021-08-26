<?php

namespace Anng\lib\contract\db;

use Anng\lib\db\Query;

interface QueryInterface
{
    /**
     * @name: 判断记录是事存在
     * @author: ANNG
     * @return bool
     */
    // public function exists(): bool;

    public function getOption($name);
}
