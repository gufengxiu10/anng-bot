<?php

declare(strict_types=1);

namespace Anng\plug\oss\aliyun;

use Anng\plug\oss\aliyun\http\Client as HttpClient;
use Anng\plug\oss\aliyun\module\Objects;
use Anng\plug\oss\contract\AuthInterface;
use Anng\plug\oss\contract\ClientInterface;
use Anng\plug\oss\contract\ObjectsInterface;

class Client implements ClientInterface
{
    public function auth(string $ak, string $as): AuthInterface
    {
        return new Auth($ak, $as);
    }

    public function objects(AuthInterface $auth): ObjectsInterface
    {
        $client = new HttpClient($auth->getAk(), $auth->getAs(), $auth->getUrl());
        return new Objects($client, $auth);
    }
}
