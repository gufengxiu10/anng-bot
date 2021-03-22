<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Http\Request as HttpRequest;

class Request
{
    private $request;

    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    public function uri()
    {
        return $this->request->server['request_uri'];
    }

    public function method()
    {
        return strtolower($this->request->server['request_method']);
    }
}
