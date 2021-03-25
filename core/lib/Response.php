<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Http\Response as SwResponse;

class Response
{
    private $response;

    public function send(SwResponse $response)
    {
        $this->response = $response;
        return $this;
    }

    public function end($data)
    {
        $this->response->end($data);
        return $this;
    }

    public function clear()
    {
        $this->response = null;
        return $this;
    }
}
