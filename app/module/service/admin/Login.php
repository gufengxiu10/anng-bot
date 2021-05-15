<?php

declare(strict_types=1);

namespace app\module\service\admin;

use Anng\lib\facade\Db;
use Anng\lib\facade\Env;
use Anng\lib\facade\Redis;
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

        $payload = [
            //JWT签发者
            "iss"   => "admin",
            //签发时间
            "aud"   => time(),
            //过期时间
            "iat"   => time() + 7200,
            //该时间之前不接收处理该Token
            "nbf"   => time() + 7200,
            //面向的用户
            "sub" => $info->id,
            //该token唯一标识
            "jti"   => '',
            "ip"    => '127.0.0.1',
            "token" => md5(uniqid('admin') . $info->id)
        ];

        Redis::set('admin:token:' . $payload['token']);

        $token = JWT::encode($payload, Env::get('key'));
        return $token;
    }

    public function exit()
    {
        # code...
    }
}
