<?php

declare(strict_types=1);

namespace Anng\lib;

use Swoole\Http\Request as HttpRequest;

class Request
{
    private $request = null;
    private $param = [];

    public function send(HttpRequest $request)
    {
        $this->request = $request;
        $this->setParam();
    }

    public function setParam(): void
    {
        if ($this->request->header['content-type'] == 'application/json') {
            $this->param = json_decode($this->request->getContent(), true);
        } else {
            if ($this->method() != 'get') {
                $this->param = $this->request->post;
            } else {
                $this->param = $this->request->get;
            }
        }
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
        if (!empty($name)) {
            return $this->param[$name] ?? $defalut;
        }
        return $this->param;
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
