<?php

declare(strict_types=1);

namespace Anng\plug\oss\contract;

interface AuthInterface
{
    //设置存储空间
    public function setBucket(string $bucket): static;

    //获得存储空间
    public function getBucket(): string;


    public function setUrl(string $url): static;

    public function getUrl(): string;


    //获得ak
    public function getAk(): string;
    public function getAs(): string;

    //获得ak
    public function getNum(): int;
    public function setNum(int $num): static;
}
