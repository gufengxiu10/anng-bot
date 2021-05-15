<?php

declare(strict_types=1);

namespace Anng\plug\oss;

use Anng\plug\oss\contract\AuthInterface;
use Anng\plug\oss\contract\ClientInterface;
use Anng\plug\oss\contract\ObjectsInterface;
use Exception;

class Oss
{
    private AuthInterface $auth;
    private ObjectsInterface $objects;

    public function __construct(private ClientInterface $client, private string $ak, private string $as)
    {
        $this->auth = $this->client->auth($ak, $as);
        $this->objects = $this->client->objects($this->auth);
    }

    private function obj()
    {
        if (empty($this->objects)) {
            $this->objects = $this->client->objects($this->auth);
        }

        return $this->objects;
    }

    public function __call($method, $args = [])
    {
        if (method_exists($this, $method)) {
            $data = call_user_func_array([$this, $method], $args);
        } else if (method_exists($this->auth, $method)) {
            $data = call_user_func_array([$this->auth, $method], $args);
        } else if (method_exists($this->objects, $method)) {
            $data = call_user_func_array([$this->objects, $method], $args);
        } else {
            throw new Exception('方法不存在');
        }

        if (is_object($data)) {
            return $this;
        }

        return $data;
    }
}
