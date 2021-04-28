<?php

declare(strict_types=1);

namespace app\module\service\admin;

use Anng\lib\Service;

class Common extends Service
{
    /**
     * @name: 密码加密
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function passwordEncry($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @name: 
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function check($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
