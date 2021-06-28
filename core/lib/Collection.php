<?php

declare(strict_types=1);

namespace Anng\lib;

use Illuminate\Support\Collection as SupportCollection;

class Collection extends SupportCollection
{
    public function __get($key)
    {
        return $this->get($key);
    }

    public function __set($key, $value)
    {
        return $this->put($key, $value);
    }
}
