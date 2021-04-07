<?php

declare(strict_types=1);

namespace Anng\plug\oss\aliyun\http;

use OSS\Http\RequestCore as HttpRequestCore;
use Swlib\Saber;
use Swlib\Saber\Response;

class RequestCore extends HttpRequestCore
{
    /**
     * Send the request, calling necessary utility functions to update built-in properties.
     *
     * @param boolean $parse (Optional) Whether to parse the response with ResponseCore or not.
     * @return string The resulting unparsed data from the request.
     */
    public function send_request($parse = false, $options = []): void
    {
        set_time_limit(0);

        $config = [
            'retry_time' => 3,
            'timeout' => $this->timeout,
            'referer' => $this->request_url,
            'useragent' => $this->useragent,
            'headers' => array_merge($this->request_headers, ['content-length' => (string)$this->read_stream_size]),
        ];

        $saber = Saber::create($config);
        switch ($this->method) {
            case self::HTTP_PUT:
                $res = $saber->put($this->request_url, $this->request_body, []);
                break;
            case self::HTTP_POST:
                $res = $saber->post($this->request_url, $this->request_body);
                break;
            default: // Assumed GET
                $res = $saber->post($this->request_url, $this->request_body);
                break;
        }

        $this->response = $res;
    }

    public function get_response_header($header = null)
    {
        if (!is_null($header)) {
            return $this->response->getHeader($header);
        }

        // return $this->response->getHeaders();
    }

    public function __call($method, $args = [])
    {
        return call_user_func_array([$this->response, $method], $args);
    }
}
