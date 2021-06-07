<?php

declare(strict_types=1);

namespace app\traits;

use Anng\lib\Collection;
use Anng\lib\facade\ResponseJson;

trait Api
{
    protected $apiCode = 200;
    protected $apiMessage = '成功';

    protected function apiBase(array|string|Collection $data = [])
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }


        $data = ResponseJson::json([
            'code'  => $this->apiCode,
            'msg'   => $this->apiMessage,
            'data'  => $data,
        ]);

        return $data;
    }

    protected  function code(int $code)
    {
        $this->apiCode = $code;
        return $this;
    }

    protected function message(string $msg)
    {
        $this->apiMessage = $msg;
        return $this;
    }

    protected  function success($data = [])
    {
        return $this->apiBase($data);
    }

    protected function error($data = [])
    {
        if ($this->apiCode == 200) {
            $this->code(400);
        }

        if ($this->apiMessage == '成功') {
            $this->message('错误');
        }

        return $this->apiBase($data);
    }
}
