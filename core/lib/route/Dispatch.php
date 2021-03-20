<?php

declare(strict_types=1);

namespace Anng\lib\route;

class Dispatch
{
    private $request;

    public function send($request)
    {
        $this->request = $request;
        return $this;
    }

    public function dispath()
    {
        $url = (new Url)->parseUrl($this->request->server['request_uri']);
    }
}
