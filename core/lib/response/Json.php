<?php

declare(strict_types=1);


namespace Anng\lib\response;

use Anng\lib\contract\response\Response as ResponseResponse;
use Anng\lib\db\Collection;
use Anng\lib\facade\Exeception;
use Anng\lib\Response;
use Throwable;

class Json extends Response implements ResponseResponse
{
    private $isEnd = false;

    public function isEnd()
    {
        return $this->isEnd;
    }

    public function json($data)
    {
        $this->sendData($data);
        return $this;
    }

    private function toJson()
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }

    public function sendData($data)
    {
        if ($data instanceof Throwable) {
            $this->data = Exeception::render($data);
        } elseif ($data instanceof Collection) {
            $this->data = $data->toArray();
        } else {
            $this->data = $data;
        }

        return $this;
    }

    public function end()
    {
        $this->isEnd = true;
        $data = is_array($this->data) ? $this->toJson() : $this->data;
        $this->response->end($data);
        return $this;
    }
}
