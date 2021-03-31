<?php

declare(strict_types=1);

namespace Anng\lib\cache;

use Anng\lib\cache\module\File;

class Cache
{
    private $module = [
        'file' => File::class
    ];

    private $client;

    public function __construct()
    {
        $this->load();
    }

    public function load()
    {
        $this->client = (new $this->module['file'])->create();
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->client, $method], $args);
    }
}
