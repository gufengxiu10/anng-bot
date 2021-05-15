<?php

declare(strict_types=1);

namespace Anng\plug\oss;

use Anng\lib\facade\Config;
use Anng\plug\oss\aliyun\Client;
use Anng\plug\oss\aliyun\module\Objects;

class Oss
{
    private $auth;

    public function __construct()
    {
        $this->auth = new Auth(Config::get('oss.ak'), Config::get('oss.sk'));
        $this->auth->setDrive(Config::get('oss.drive'))->setBucket(Config::get('oss.bucket'));
        $this->object = $this->getModule();
    }

    private function getModule()
    {
        if ($this->auth->client() instanceof Client) {
            return (new Objects($this->auth));
        }
    }

    public function getClientType()
    {
        if ($this->object instanceof Objects) {
            return 'aliyun';
        } else {
            return 'location';
        }
    }

    public function getBucket()
    {
        return $this->auth->getBucket();
    }

    public function __call($method, $args = [])
    {
        return call_user_func_array([$this->object, $method], $args);
    }
}
