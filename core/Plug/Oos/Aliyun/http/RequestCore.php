<?php

declare(strict_types=1);

namespace Anng\Plug\Oos\Aliyun\http;

use OSS\Http\RequestCore as HttpRequestCore;
use Swlib\Saber;
use Swoole\Coroutine\Http\Client;

class RequestCore extends HttpRequestCore
{
    /**
     * Send the request, calling necessary utility functions to update built-in properties.
     *
     * @param boolean $parse (Optional) Whether to parse the response with ResponseCore or not.
     * @return string The resulting unparsed data from the request.
     */
    public function send_request($parse = false, $options = [])
    {
        set_time_limit(0);

        $config = [
            'retry_time' => 3,
            'timeout' => $this->timeout,
            'referer' => $this->request_url,
            'useragent' => $this->useragent,
            'headers' => array_merge($this->request_headers, ['content-length' => $this->read_stream_size]),
        ];

        dump($this->request_headers);
        dump($config);

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

        // dump($res);
        // $saber = Saber::create();

        // $curl_handle = $this->prep_request();
        // $this->response = curl_exec($curl_handle);

        // if ($this->response === false) {
        //     throw new RequestCore_Exception('cURL resource: ' . (string)$curl_handle . '; cURL error: ' . curl_error($curl_handle) . ' (' . curl_errno($curl_handle) . ')');
        // }

        // $parsed_response = $this->process_response($curl_handle, $this->response);

        // curl_close($curl_handle);

        // if ($parse) {
        //     return $parsed_response;
        // }

        // return $this->response;
    }

    /**
     * Prepare and adds the details of the cURL request. This can be passed along to a <php:curl_multi_exec()>
     * function.
     *
     * @return resource The handle for the cURL object.
     *
     */
    public function prep_request()
    {
    }
}
