<?php

declare(strict_types=1);

namespace Anng\lib;

use Anng\lib\file\UploadFile;
use Swoole\Http\Request as HttpRequest;

class Request
{
    private $request = null;
    private $param = [];
    private array $header = [];
    private array|null $files = [];

    public static function make()
    {
        # code...
    }

    public function send(HttpRequest $request)
    {
        $this->request = $request;
        $this->header = $this->request->header;
        $this->files = $this->request->files;
        $this->setParam();
        return $this;
    }


    public function has($name, $type = 'param', $isEmpty = false)
    {
        $data = '';
        switch ($type) {
            case 'param':
                $data = $this->param[$name] ??  null;
                break;
            case 'header':
                $data = $this->header[$name] ??  null;
                break;
            default:
                $data = $this->param[$name] ??  null;
        }

        if ($isEmpty === true && empty($data)) {
            return false;
        }

        return !is_null($data) ? true : false;
    }

    public function setParam(): void
    {
        if (isset($this->header['content-type']) && str_contains($this->header['content-type'], 'application/json')) {
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

    public function file($name = '')
    {
        if (empty($name)) {
            $name = 'file';
        }

        if (isset($this->files[$name])) {
            $file = $this->files[$name];
            return UploadFile::make($file['tmp_name'], $file['name'], $file['error']);
        }

        return false;
    }

    public function files()
    {
        # code...
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
