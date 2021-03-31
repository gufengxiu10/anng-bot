<?php

declare(strict_types=1);

namespace Anng\Plug\Oos;

use Anng\Plug\Oos\Aliyun\Client;
use Anng\Plug\Oos\Aliyun\module\Objects;

class Oos
{
    private $auth;

    public function __construct($auth)
    {
        $this->auth = $auth;
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
