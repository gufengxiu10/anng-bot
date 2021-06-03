<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\contract\response\Response as ResponseResponse;
use Anng\lib\facade\Exeception;
use Swoole\Http\Response as SwResponse;
use Throwable;

abstract class Response implements ResponseResponse
{
    private string $data;

    public function __construct(protected SwResponse $response)
    {
        # code...
    }

    public function header()
    {
        # code...
    }


    

    abstract public function end();

    public function __call($method, $args = [])
    {
        call_user_func_array([$this->response, $method], $args);
        return $this;
    }
}
