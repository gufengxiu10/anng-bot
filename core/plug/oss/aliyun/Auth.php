<?php

declare(strict_types=1);

namespace Anng\plug\oss\aliyun;

use Anng\plug\oss\contract\AuthInterface;
use Anng\plug\oss\contract\ClientInterface;

class Auth implements AuthInterface
{
    private string $bucket;
    private string $url = 'http://oss-cn-shenzhen.aliyuncs.com';
    private int $num = 5;

    public function __construct(private string $ak, private string $as)
    {
        # code...
    }


    public function setNum(int $num): static
    {
        $this->num = $num;
        return $this;
    }

    public function getNum(): int
    {
        return $this->num;
    }

    public function getAk(): string
    {
        return $this->ak;
    }

    public function getAs(): string
    {
        return $this->as;
    }

    public function setBucket(string $bucket): static
    {
        $this->bucket = $bucket;
        return $this;
    }

    public function getBucket(): string
    {
        return $this->bucket;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }


    public function getUrl(): string
    {
        return $this->url;
    }
}
