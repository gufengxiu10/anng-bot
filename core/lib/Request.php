<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Http\Request as HttpRequest;

class Request
{
    private $request = null;

    public function send(HttpRequest $request)
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

    public function isMethod($method)
    {
        return $this->method() == $method ?  true : false;
    }

    public function param($name = '', $defalut = '')
    {
        $method = $this->method();
        if (!empty($name)) {
            return $this->request->$method[$name] ?? $defalut;
        }
        return $this->request->$method;
    }

    /**
     * @name: 获得头信息
     * @param {*} $name
     * @author: ANNG
     * @todo: 
     * @Date: 2021-03-24 10:56:02
     * @return {*}
     */
    public function getHeader($name = '')
    {
        if (empty($name)) {
            return $this->request->header;
        }

        return $this->request->header[$name];
    }

    public function clear()
    {
        $this->request = null;
    }
}
