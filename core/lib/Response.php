<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Http\Response as SwResponse;

class Response
{
    private $instances = [];

    private $response;

    public function send(SwResponse $response)
    {
        $this->response = $response;
    }

    public function end($data)
    {
        $this->response->end($data);
    }
}
