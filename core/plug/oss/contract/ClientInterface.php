<?php

declare(strict_types=1);

namespace Anng\plug\oss\contract;

interface ClientInterface
{
    public function auth(string $ak, string $as): AuthInterface;

    public function objects(AuthInterface $auth): ObjectsInterface;
}
