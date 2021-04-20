<?php

declare(strict_types=1);

namespace Anng\lib;

abstract class Provider
{
    public function __construct(protected App $app)
    {
        # code...
    }
}
