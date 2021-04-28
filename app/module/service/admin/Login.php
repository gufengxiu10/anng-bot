<?php

declare(strict_types=1);

namespace app\module\service\admin;

use Anng\lib\facade\Db;
use Anng\lib\facade\Env;
use Anng\lib\Service;
use Exception;
use Firebase\JWT\JWT;
use PhpToken;

class Login extends Service
{
    protected $loads = [
        'common'    => Common::class,
        'admin'     => Admin::class
    ];

    /**
     * @name: 登录
     * @param {*}
     * @author: ANNG
     * @return {*}
     */
    public function login($data)
    {
        $info = $this->admin->info([
            'where' => fn ($db) => $db->where('name', $data['name'])
        ]);

        if (!$this->common->check($data['password'], $info->password)) {
            throw new Exception('密码错误');
        }

        $payload = array(
            "iss" => "admin",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );

        $token = JWT::encode($payload, Env::get('key'));
        dump(Env::get('key'));
        dump($token);
    }
}
