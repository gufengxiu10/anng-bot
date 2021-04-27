<?php

declare(strict_types=1);

namespace app\module\service\admin;

use Anng\lib\facade\Db;
use Anng\lib\Service;
use Exception;

class Admin extends Service
{
    protected $loads = [
        'common' => Common::class
    ];

    public function info($option = [])
    {
        $info = Db::name('admin')
            ->where(fn () => $option['where'] ?? '')
            ->find();

        if (!$info) {
            throw new Exception('信息不存在');
        }
        return $info;
    }

    public function check($name = '', $email = '')
    {
        if (empty($name) && empty($email)) return false;
        try {
            $info = $this->info([
                'where' => function ($db) use ($name, $email) {
                    if (!empty($name)) {
                        $db->where('name', $name);
                    }

                    if (!empty($email)) {
                        $db->where('email', $email);
                    }
                }
            ]);

            return !$info->isEmpty();
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * @name: 添加管理员
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function add($data)
    {
        if ($this->check($data['name'])) {
            throw new Exception('管理员已存在');
        }

        $password = $this->common->passwordEncry($data['password']);
        $info = Db::name('admin')->insert([
            'name'  => $data['name'],
            'password'  => $password
        ]);

        return $info ? true : false;
    }
}
